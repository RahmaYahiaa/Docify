<?php

namespace App\Actions\Patient\Profile;

use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateMedicalDataAction
{
    use AsAction;

    public function execute(User $user, array $data)
    {
        $patientProfile = $user->patientProfile;
        $medicalData = $patientProfile->medicalData ?? $patientProfile->medicalData()->create([]);

        $medicalData->update([
            'blood_type' => $data['blood_type'] ?? $medicalData->blood_type,
            'height' => $data['height'] ?? $medicalData->height,
            'weight' => $data['weight'] ?? $medicalData->weight,
        ]);

        if (isset($data['chronic_conditions'])) {
            $patientProfile->chronicConditions()->sync($data['chronic_conditions']);
        }

        if (isset($data['allergies'])) {
            $patientProfile->allergies()->sync($data['allergies']);
        }

        return $medicalData->load('patientProfile.chronicConditions', 'patientProfile.allergies');
    }
}
