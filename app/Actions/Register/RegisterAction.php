<?php

namespace App\Actions\Register;

use App\Enums\Role\UserRoleEnum;
use App\Models\HealthCard;
use App\Models\User\User;

class RegisterAction
{
    public function execute(array $data): User
    {
        $user = User::create($data);

        $user->assignRole(UserRoleEnum::PATIENT);

        $profile = $user->patientProfile()->create([]);

        $profile->medicalData()->create([]);

        HealthCard::create(['user_id' => $user->id]);

        return $user;
    }
}
