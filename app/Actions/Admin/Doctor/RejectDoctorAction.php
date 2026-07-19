<?php

namespace App\Actions\Admin\Doctor;

use App\Enums\Doctor\DoctorStatusEnum;
use App\Enums\User\UserStatusEnum;
use App\Models\User\User;

class RejectDoctorAction
{
    public function execute(User $doctor, string $reason): User
    {
        $doctor->update([
            'rejection_reason' => $reason,
            'status' => UserStatusEnum::REJECTED->value,
        ]);

        $doctor->doctorProfile?->update([
            'rejected_at' => now(),
        ]);

        activity()
            ->performedOn($doctor)
            ->causedBy(auth()->user())
            ->withProperties([
                'reason' => $reason,
                'status' => DoctorStatusEnum::REJECTED->value,
            ])
            ->log('Doctor Rejected');

        return $doctor;
    }
}
