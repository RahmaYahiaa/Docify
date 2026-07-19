<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\UserMeasurement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserMeasurementSeeder extends Seeder
{
    public function run(): void
    {
        $patients = User::role('patient')->limit(10)->get();

        $typeConfigs = [
            1 => ['name' => 'Blood Pressure', 'min' => 110, 'max' => 135, 'min2' => 70, 'max2' => 88],
            2 => ['name' => 'Heart Rate',      'min' => 60,  'max' => 100, 'min2' => null, 'max2' => null],
            3 => ['name' => 'Blood Sugar',     'min' => 80,  'max' => 140, 'min2' => null, 'max2' => null],
            4 => ['name' => 'Temperature',     'min' => 360, 'max' => 375, 'min2' => null, 'max2' => null], // stored as *10
            5 => ['name' => 'Sleep Hours',     'min' => 5,   'max' => 9,   'min2' => null, 'max2' => null],
            6 => ['name' => 'Weight',          'min' => 55,  'max' => 95,  'min2' => null, 'max2' => null],
            7 => ['name' => 'Oxygen Saturation', 'min' => 95, 'max' => 100, 'min2' => null, 'max2' => null],
        ];

        // Track which types each patient should track (realistic — not everyone tracks everything)
        $patientTypeMap = [
            0 => [1, 2, 3],     // Blood pressure, heart rate, blood sugar (hypertensive + diabetic)
            1 => [1, 2, 5],     // BP, heart rate, sleep
            2 => [2, 5, 7],     // Heart rate, sleep, oxygen
            3 => [1, 3, 6],     // BP, blood sugar, weight
            4 => [1, 2, 3, 6],  // Full monitoring (elderly)
            5 => [2, 5, 6],     // Basic vitals
            6 => [3, 6],        // Diabetic tracking
            7 => [1, 2],        // BP monitoring
            8 => [5, 7],        // Sleep + oxygen
            9 => [2, 6],        // Weight + heart rate
        ];

        foreach ($patients as $idx => $patient) {
            $typeIds = $patientTypeMap[$idx] ?? [1, 2];

            foreach ($typeIds as $typeId) {
                $config = $typeConfigs[$typeId];

                // 30 days of readings
                for ($i = 29; $i >= 0; $i--) {
                    $measuredAt = Carbon::now()->subDays($i)->setHour(rand(7, 9));

                    // Skip some days (realistic — not every day)
                    if ($i > 0 && rand(1, 7) === 1) {
                        continue;
                    }

                    $rawValue = rand($config['min'], $config['max']);
                    $value    = $typeId === 4 ? $rawValue / 10 : $rawValue; // Temperature fix

                    UserMeasurement::firstOrCreate(
                        [
                            'user_id'             => $patient->id,
                            'measurement_type_id' => $typeId,
                            'measured_at'         => $measuredAt->format('Y-m-d H:i:s'),
                        ],
                        [
                            'value'   => $value,
                            'value_2' => $config['min2'] ? rand($config['min2'], $config['max2']) : null,
                            'note'    => null,
                        ]
                    );
                }
            }
        }
    }
}