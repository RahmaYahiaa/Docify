<?php

namespace App\Actions\Patient\Appointment;

use App\Actions\Payment\DoctorPayoutAction;
use App\Actions\Payment\RefundPaymentAction;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Enums\Payment\PayoutTypeEnum;
use App\Events\Notification\AppointmentCancelled;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentException;
use App\Models\Appointment;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;


class CancelAppointmentAction
{
    public function __construct(
        private readonly RefundPaymentAction $refundPaymentAction,
        private readonly DoctorPayoutAction $doctorPayoutAction,
    ) {}

    public function execute(Appointment $appointment, User $patient, array $data): void
    {
        if ($appointment->patient_id !== $patient->id) {
            throw new ForbiddenException(__('messages.unauthorized_action'));
        }

        if (!$appointment->isCancellable()) {
            throw new InvalidArgumentException(__('messages.cannot_cancel_finalized_appointment'));
        }

        DB::transaction(function () use ($appointment, $patient, $data) {

            $appointment->update([
                'status' => AppointmentStatusEnum::CANCELLED,
                'cancelled_at' => now(),
                'cancel_reason' => $data['reason'] ?? null,
                'cancel_notes' => $data['notes'] ?? null,
                'cancelled_by' => $patient->id,
            ]);

            // activity()
            //     ->performedOn($appointment)
            //     ->causedBy($patient)
            //     ->withProperties([
            //         'status' => AppointmentStatusEnum::CANCELLED->value,
            //         'message' => "Appointment cancelled by {$appointment->patient?->full_name}",
            //     ])
            //     ->log('Appointment Cancelled');

            $appointment->slot->update([
                'status' => AvailabilitySlotStatusEnum::AVAILABLE,
            ]);

            if (!$appointment->requiresPayment()) {
                return;
            }

            if (!$appointment->payment?->isPaid()) {
                return;
            }

            if ($appointment->isRefundable()) {
                $this->refundPaymentAction->execute($appointment);
            } else {
                $this->doctorPayoutAction->execute($appointment, PayoutTypeEnum::LATE_CANCEL);
            }
        });

        event(new AppointmentCancelled($appointment,$patient->full_name));
    }
}
