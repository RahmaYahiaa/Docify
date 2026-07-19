<?php

namespace App\Actions\Doctor\Profile\Fee;

use App\Models\DoctorProfile;
use App\Models\User\User;

class UpdateConsultationFeesAction
{
    public function execute(DoctorProfile $profile, array $data)
    {

$profile->update($data);
return $profile;

    }
}