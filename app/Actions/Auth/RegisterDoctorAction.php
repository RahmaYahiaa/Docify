<?php

namespace App\Actions\Auth;

use App\Models\DoctorProfile;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class RegisterDoctorAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => $data['password'],
                'status'     => 'pending',
            ]);

            $user->assignRole('doctor');
            DoctorProfile::create([
                'user_id'           => $user->id,
                'specialization_id' => $data['specialization_id'],
            ]);

            $user
                ->addMedia($data['certificates'])
                ->toMediaCollection(User::MEDICAL_CERTIFICATE);

            return $user;
        });
    }
}
