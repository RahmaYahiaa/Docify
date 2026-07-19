<?php

namespace App\Actions\Payment;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Enums\Payment\PaymentStatusEnum;
use App\Enums\Payment\PayoutStatusEnum;
use App\Events\Notification\AppointmentBooked;
use App\Models\Appointment;
use App\Models\DoctorAvailabilitySlot;
use App\Models\Payment;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stripe\PaymentIntent;

class HandleSuccessfulPaymentAction
{
    public function __construct(
        private readonly StripeService $stripeService
    ) {}

    public function execute(PaymentIntent $paymentIntent): void
    {
        $cacheKey = $paymentIntent->metadata->cache_key ?? null;

        if (!$cacheKey) {
            Log::error('Webhook: missing cache_key', ['payment_intent_id' => $paymentIntent->id]);
            return;
        }

        $cached = Cache::get($cacheKey);

        if (!$cached) {
            Log::error('Webhook: cache expired', [
                'cache_key' => $cacheKey,
                'payment_intent_id' => $paymentIntent->id,
            ]);
            return;
        }

        // Idempotency check
        if (Payment::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
            Log::info('Webhook: already processed', ['payment_intent_id' => $paymentIntent->id]);
            return;
        }
        $appointmentId = null;
        DB::transaction(function () use ($cached, $paymentIntent, $cacheKey, &$appointmentId) {

            $slot = DoctorAvailabilitySlot::where('id', $cached['slot_id'])
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw new RuntimeException('Slot not found: ' . $cached['slot_id']);
            }
            if (!$slot->isLocked()) {
                Log::warning('Webhook: slot expired — full refund', [
                    'slot_id' => $cached['slot_id'],
                    'payment_intent_id' => $paymentIntent->id,
                ]);

                $this->stripeService->refund(
                    paymentIntentId: $paymentIntent->id,
                    amountInCents: (int) round($cached['amount'] * 100),
                );

                Cache::forget($cacheKey);
                throw new RuntimeException('Slot no longer locked — refund initiated');
            }

            $appointment = Appointment::create([
                'patient_id' => $cached['patient_id'],
                'doctor_id' => $cached['doctor_id'],
                'slot_id' => $slot->id,
                'type' => 'video',
                'status' => AppointmentStatusEnum::CONFIRMED,
                'confirmed_at' => now(),
                'reason_for_visit' => $cached['reason_for_visit'],
                'notes' => $cached['notes'],
            ]);

            // activity()
            //     ->performedOn($appointment)
            //     ->causedBy(auth()->user())
            //     ->withProperties([
            //         'amount' => "Amount: {$appointment->payment?->amount}",
            //         'status' => AvailabilitySlotStatusEnum::BOOKED,
            //         'message' => "Amount: {$appointment->payment?->amount} - {$appointment->payment?->payment_method}",
            //     ])
            //     ->log('Payment Processed');

            $slot->update([
                'status' => AvailabilitySlotStatusEnum::BOOKED,
                'locked_until' => null,
            ]);

            Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $cached['patient_id'],
                'doctor_id' => $cached['doctor_id'],
                'amount' => $cached['amount'],
                'consultation_fee' => $cached['consultation_fee'],
                'platform_fee' => $cached['platform_fee'],
                'doctor_amount' => $cached['doctor_amount'],
                'platform_amount' => $cached['platform_amount'],
                'currency' => config('stripe.currency'),
                'payment_method' => 'card',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => PaymentStatusEnum::PAID,
                'payout_status' => PayoutStatusEnum::PENDING,
                'paid_at' => now(),
            ]);
            $appointmentId = $appointment->id;
        });

        Cache::put("payment_result_{$cacheKey}", [
            'status' => 'confirmed',
            'appointment_id' => $appointmentId,
            'patient_id' => $cached['patient_id'],
        ], now()->addMinutes(30));

        Cache::forget($cacheKey);

        Log::info('Webhook: appointment confirmed', [
            'appointment_id' => $appointmentId,
            'payment_intent_id' => $paymentIntent->id,
        ]);

        $appointment = Appointment::with([
            'patient',
            'doctor',
            'slot',
            'slot.clinic'
        ])->findOrFail($appointmentId);

        event(new AppointmentBooked($appointment));
    }
}
