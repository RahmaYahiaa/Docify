<?php

namespace App\Actions\Patient\Appointment;

use App\Enums\Appointment\AppointmentTypeEnum;
use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\Jobs\ReleaseLockedSlotJob;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InitiateAppointmentAction
{
    public function __construct(
        private readonly StripeService $stripeService
    ) {}
    public function execute(User $patient, array $data): array
    {
        $slot = DB::transaction(function () use ($data) {

            $slot = DoctorAvailabilitySlot::where('id', $data['slot_id'])
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw new NotFoundException(__('messages.appointment_slot_not_found_or_unavailable'));
            }
            
            if ($slot->date->setTimeFromTimeString($slot->getRawOriginal('start_time'))->isPast()) {
                throw new InvalidArgumentException(__('messages.appointment_slot_in_the_past'));
            }

            if ($slot->type !== AppointmentTypeEnum::VIDEO) {
                throw new InvalidArgumentException(__('messages.invalid_consultation_type'));
            }

            if (!$slot->isAvailable()) {
                throw new NotFoundException(__('messages.appointment_slot_not_found_or_unavailable'));
            }

            $slot->update([
                'status' => AvailabilitySlotStatusEnum::LOCKED,
                'locked_until' => now()->addMinutes(10),
            ]);

            return $slot;
        });

        // Calculate fees
        $slot->load('doctor.doctorProfile');
        $doctorProfile = $slot->doctor?->doctorProfile;

        if (!$doctorProfile || is_null($doctorProfile->video_fee)) {
            $slot->update([
                'status' => AvailabilitySlotStatusEnum::AVAILABLE,
                'locked_until' => null,
            ]);
            throw new InvalidArgumentException(__('messages.doctor_video_fee_not_set'));
        }

        $consultationFee = $doctorProfile->video_fee;

        $platformFeePct = config('stripe.platform_fee_percentage') / 100;
        $platformFee = round($consultationFee * $platformFeePct, 2);
        $totalAmount = $consultationFee + $platformFee;

        $doctorPct = config('stripe.doctor_percentage') / 100;
        $doctorAmount = round($consultationFee * $doctorPct, 2);
        $platformAmount = $totalAmount - $doctorAmount;

        $cacheKey = 'appointment_initiation_' . Str::uuid();

        try {
            $paymentIntent = $this->stripeService->createPaymentIntent(
                amountInCents: (int) round($totalAmount * 100),
                currency: config('stripe.currency'),
                metadata: [
                    'cache_key' => $cacheKey,
                    'patient_id' => $patient->id,
                    'slot_id' => $slot->id,
                    'doctor_id' => $slot->doctor_id,
                ]
            );
        } catch (\Exception $e) {
            $slot->update([
                'status' => AvailabilitySlotStatusEnum::AVAILABLE,
                'locked_until' => null,
            ]);
            throw $e;
        }
        Cache::put($cacheKey, [
            'slot_id' => $slot->id,
            'patient_id' => $patient->id,
            'doctor_id' => $slot->doctor_id,
            'reason_for_visit' => $data['reason_for_visit'] ?? null,
            'notes' => $data['notes'] ?? null,
            'amount' => $totalAmount,
            'consultation_fee' => $consultationFee,
            'platform_fee' => $platformFee,
            'doctor_amount' => $doctorAmount,
            'platform_amount' => $platformAmount,
            'payment_intent_id'  => $paymentIntent->id,
        ], now()->addMinutes(10));

        ReleaseLockedSlotJob::dispatch($slot->id)
            ->delay(now()->addMinutes(10));

        return [
            'cache_key' => $cacheKey,
            'client_secret' => $paymentIntent->client_secret,
            'expires_at' => now()->addMinutes(10)->toDateTimeString(),
            'amount' => $totalAmount,
            'cost_breakdown' => [
                'consultation_fee' => $consultationFee,
                'platform_fee' => $platformFee,
            ],
        ];
    }
}
