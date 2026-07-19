<?php

namespace App\Actions\Doctor\Profile\ProfilePicture;

use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class  UploadDoctorPictureAction
{
    use AsAction;

    public function execute(User $user, $file): User
    {
        if ($file && $user->doctorProfile) {
            $user->doctorProfile->clearMediaCollection('profile_picture');
            $user->doctorProfile->addMedia($file)
                ->toMediaCollection('profile_picture');
        }

        return $user;
    }
}