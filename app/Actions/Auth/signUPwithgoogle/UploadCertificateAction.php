<?php

namespace App\Actions\Auth\signUPwithgoogle;

use App\Models\User\User;



class UploadCertificateAction
{
    public function execute(User $user, string $field,  ?int $specializationId = null): void
    {

        $user->doctorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            ['specialization_id' => $specializationId]
        );

        $user->addMediaFromRequest($field)
            ->toMediaCollection(User::MEDICAL_CERTIFICATE);
    }
}