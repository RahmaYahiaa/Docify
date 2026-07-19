<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            PermissionSeeder::class,       // ← added
            SpecializationSeeder::class,
            ClinicSeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            DoctorAvailabilitySlotSeeder::class,
            AppointmentSeeder::class,
            ChronicConditionSeeder::class,
            AllergySeeder::class,
            MedicationSeeder::class,
            PatientProfileSeeder::class,
            PrescriptionSeeder::class,
            ReviewSeeder::class,
            PaymentSeeder::class,
            MeasurementTypeSeeder::class,
            UserMeasurementSeeder::class,
            SettingSeeder::class,
        ]);
    }
}