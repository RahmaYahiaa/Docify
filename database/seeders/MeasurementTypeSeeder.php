<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['id' => 1, 'name' => 'Blood Pressure', 'unit' => 'mmHg',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Heart Rate',      'unit' => 'bpm',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Blood Sugar',     'unit' => 'mg/dL', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Temperature',     'unit' => '°C',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Sleep Hours',     'unit' => 'hours', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Weight',          'unit' => 'kg',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Oxygen Saturation', 'unit' => '%',   'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('measurement_types')->upsert($types, ['id'], ['name', 'unit', 'updated_at']);
    }
}