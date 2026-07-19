<?php

namespace App\Actions\Patient\Profile;

use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProfileAction
{
    use AsAction;

    public function execute(User $user, array $data): User
    {
        $user->update([
            'first_name' => $data['first_name'] ?? $user->first_name,
            'last_name'  => $data['last_name'] ?? $user->last_name,
            'email'      => $data['email'] ?? $user->email,
            'phone'      => $data['phone'] ?? $user->phone,
        ]);

        $profile = $user->patientProfile;

        $profile->update([
            'date_of_birth' => $data['date_of_birth'] ?? $profile->date_of_birth,
            'gender'        => $data['gender'] ?? $profile->gender,
            'address'       => $data['address'] ?? $profile->address,
            'emergency_contact' => $data['emergency_contact'] ?? $profile->emergency_contact,
        ]);

        return $user->load('patientProfile');
    }
}
