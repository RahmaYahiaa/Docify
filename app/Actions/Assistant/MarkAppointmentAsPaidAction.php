<?php

namespace App\Actions\Assistant;

use App\Models\Appointment;
use App\Enums\Appointment\AppointmentStatusEnum;

class MarkAppointmentAsPaidAction
{

    public function execute(Appointment $appointment): Appointment
    {
        $appointment->update([
            'is_paid_cash' => true,
            'cash_collected_at' => now(),
            'status' => AppointmentStatusEnum::COMPLETED,
        ]);

        activity()
            ->performedOn($appointment)
            ->causedBy(auth()->user())
            ->withProperties([
                'status' => AppointmentStatusEnum::COMPLETED->value,
                'message' => "Payment completed for appointment #{$appointment->id}",
            ])
            ->log('Appointment Paid');

        return $appointment;
    }
}