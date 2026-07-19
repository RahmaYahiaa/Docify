<?php

namespace App\Actions\Patient\Profile;

use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadProfilePictureAction
{
    use AsAction;

    public function execute(User $user, $file): User
    {
        if ($file && $user->patientProfile) {
            $user->patientProfile->clearMediaCollection('profile_picture');
            $user->patientProfile->addMedia($file)->toMediaCollection('profile_picture');
        }
        
        return $user;
    }
}
