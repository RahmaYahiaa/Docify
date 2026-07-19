<?php

namespace App\Actions\Doctor\Profile\Language;

use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Auth;

class AddDoctorLanguagesAction
{
    public function execute(array $newLanguages): DoctorProfile
    {
        $profile = Auth::user()->doctorProfile;

        $profile->update([
            'languages' => array_values(array_unique(array_merge((array)$profile->languages, $newLanguages)))
        ]);
        return $profile;
    }
}