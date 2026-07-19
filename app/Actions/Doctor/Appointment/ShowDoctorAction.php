<?php

namespace App\Actions\Doctor\Appointment;

use App\Exceptions\ForbiddenException;
use App\Models\User\User;

class ShowDoctorAction
{
    public function execute(User $patient, User $doctor): User
    {
        if (! $doctor->isDoctor()) {
            throw new ForbiddenException(__('messages.requested_user_not_a_doctor'));
        }

        return $doctor->load(['doctorProfile.specialization', 'doctorProfile.clinic', 'doctorProfile' => function ($query) {
            $query->withReviewsStats();
        },]);
    }
}
