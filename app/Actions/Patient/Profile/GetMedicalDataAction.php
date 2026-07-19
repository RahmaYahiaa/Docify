<?php

namespace App\Actions\Patient\Profile;

use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMedicalDataAction
{
    use AsAction;

    public function execute(User $user)
    {
        return $user->load([
            'patientProfile.medicalData',
            'patientProfile.chronicConditions',
            'patientProfile.allergies',
        ]);
    }
}
