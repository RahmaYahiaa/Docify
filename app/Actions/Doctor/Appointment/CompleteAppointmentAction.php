<?php

namespace App\Actions\Doctor\Appointment;

use App\Actions\Payment\DoctorPayoutAction;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Payment\PayoutTypeEnum;
use App\Events\Notification\AppointmentCompleted;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentException;
use App\Models\Appointment;
use App\Models\DoctorCommission;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class CompleteAppointmentAction
{
    public function __construct(
        private readonly DoctorPayoutAction $doctorPayoutAction,
    ) {}

    public function execute(Appointment $appointment, User $doctor): void
    {
        if ($appointment->doctor_id !== $doctor->id) {
            throw new ForbiddenException(__('messages.unauthorized_action'));
        }

        if ($appointment->isFinal()) {
            throw new InvalidArgumentException(__('messages.appointment_already_finalized'));
        }



        DB::transaction(function () use ($appointment) {

            $appointment->update([
                'status' => AppointmentStatusEnum::COMPLETED,
            ]);

            if ($appointment->requiresPayment()) {
                if ($appointment->payment?->isPaid()) {
                    $this->doctorPayoutAction->execute(
                        $appointment,
                        PayoutTypeEnum::COMPLETED
                    );
                }
            } else {
                $this->recordInPersonCommission($appointment);
            }
        });

        DB::afterCommit(function () use ($appointment) {
            event(new AppointmentCompleted($appointment));
        });
    }

    private function recordInPersonCommission(Appointment $appointment): void
    {
        $consultationFee = $appointment->slot->doctor
            ->doctorProfile
            ->in_person_fee;

        $commissionRate   = config('stripe.platform_in_person_percentage');
        $commissionAmount = round($consultationFee * $commissionRate / 100, 2);

        DoctorCommission::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'consultation_fee'  => $consultationFee,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'type' => 'in_person',
            'status' => 'pending',
        ]);
    }
}
