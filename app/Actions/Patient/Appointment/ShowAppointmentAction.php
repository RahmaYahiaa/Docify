<?php

namespace App\Actions\Patient\Appointment;

use App\Exceptions\ForbiddenException;
use App\Models\Appointment;
use App\Models\User\User;

class ShowAppointmentAction
{
    public function execute(User $patient, Appointment $appointment): Appointment
    {
        if ($appointment->patient_id !== $patient->id) {
            throw new ForbiddenException(__('messages.unauthorized_action'));
        }

        return $appointment->load([
            'doctor.doctorProfile',
            'slot',
            'slot.clinic',
            'payment',
        ]);
    }
}
