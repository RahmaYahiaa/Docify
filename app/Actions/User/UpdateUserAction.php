<?php

namespace App\Actions\User;

use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'email'     => $data['email'] ?? $user->email,
                'phone'     => $data['phone'] ?? $user->phone,
                'status'    => $data['status'] ?? $user->status,
            ]);

            if ($user->hasRole('doctor')) {
                $user->doctorProfile()->updateOrCreate(
                    [],
                    [
                        'specialization_id' => $data['specialty'] ?? null,
                        'license_number'    => $data['license_number'] ?? null,
                    ]
                );
                if (!empty($data['certificates'])) {
                    $user->clearMediaCollection(User::MEDICAL_CERTIFICATE);
                    $user->addMedia($data['certificates'])
                        ->toMediaCollection(User::MEDICAL_CERTIFICATE);
                }
            }

            return $user->load([
                'roles',
                'doctorProfile.specialization'
            ]);
        });
    }
}
