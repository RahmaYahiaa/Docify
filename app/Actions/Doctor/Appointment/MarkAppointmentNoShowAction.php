<?php

namespace App\Actions\Doctor\Appointment;

use App\Actions\Payment\DoctorPayoutAction;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Payment\PayoutTypeEnum;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentException;
use App\Models\Appointment;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class MarkAppointmentNoShowAction
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

        $slotDateTime = $appointment->slot->date
            ->setTimeFromTimeString($appointment->slot->getRawOriginal('start_time'));

        if ($slotDateTime->isFuture()) {
            throw new InvalidArgumentException(__('messages.cannot_mark_future_appointment_no_show'));
        }

        DB::transaction(function () use ($appointment) {

            $appointment->update([
                'status' => AppointmentStatusEnum::NO_SHOW,
            ]);

            if ($appointment->requiresPayment() && $appointment->payment?->isPaid()) {
                $this->doctorPayoutAction->execute($appointment, PayoutTypeEnum::NO_SHOW);
            }
        });
    }
}
