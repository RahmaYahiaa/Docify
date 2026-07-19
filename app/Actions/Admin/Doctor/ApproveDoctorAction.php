<?php

namespace App\Actions\Admin\Doctor;

use App\Enums\Doctor\DoctorStatusEnum;
use App\Enums\User\UserStatusEnum;
use App\Models\User\User;

class ApproveDoctorAction
{
    public function execute(User $doctor): User
    {
        $doctor->update([
            'status' => UserStatusEnum::ACTIVE,
        ]);

        activity()
            ->performedOn($doctor)
            ->causedBy(auth()->user())
            ->withProperties([
                'status' => DoctorStatusEnum::ACTIVE->value,
                'message' => 'All credentials approved',
            ])
            ->log('Doctor Verified');

        return $doctor;
    }
}
