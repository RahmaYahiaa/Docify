<?php

namespace App\Actions\Patient\AI;

use App\Models\PatientProfile;
use App\Models\UserMeasurement;

class BuildPatientHealthDataAction
{
    public function execute(int $userId, string $lang = 'en'): array
    {
        $profile = PatientProfile::with(['medicalData','chronicConditions'])
            ->where('user_id', $userId)
            ->first();

        $measurements = UserMeasurement::where('user_id', $userId)
            ->whereDate('measured_at', today())
            ->latest('measured_at')
            ->get()
            ->groupBy('measurement_type_id');

        $getValue = fn ($id) =>
            optional($measurements->get($id)?->first())->value;

        $getValue2 = fn ($id) =>
            optional($measurements->get($id)?->first())->value_2;

        return [
            'profile' => [
                'age' => $profile->age ?? null,
                'gender' => $profile->gender ?? null,
                'height' => $profile?->medicalData?->height,
                'weight' => $profile?->medicalData?->weight,
                'blood_type' => $profile?->medicalData?->blood_type,
                'sleep_hours' => $getValue(5),
                'chronic_diseases' =>
                    $profile?->chronicConditions?->pluck('name')->toArray() ?? [],
            ],

            'vitals' => [
                'blood_sugar' => $getValue(3),
                'systolic' => $getValue(1),
                'diastolic' => $getValue2(1),
                'temperature' => $getValue(4),
                'heart_rate' => $getValue(2),
            ],

            'language' => $lang,
        ];
    }
}