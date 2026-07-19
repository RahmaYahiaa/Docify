<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DoctorAvailabilitySlotSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::role('doctor')->get();

        $morningTimes = ['09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00'];
        $afternoonTimes = ['14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00', '16:30:00'];
        $allTimes = array_merge($morningTimes, $afternoonTimes);

        foreach ($doctors as $doctor) {
            $profile = $doctor->doctorProfile;
            $clinic  = $profile?->clinic_id ? Clinic::find($profile->clinic_id) : Clinic::inRandomOrder()->first();

            // Past slots (30 days back) — mostly booked, some completed
            for ($day = 30; $day >= 1; $day--) {
                $date = Carbon::now()->subDays($day)->format('Y-m-d');

                // Skip Fridays (day off)
                if (Carbon::parse($date)->isFriday()) {
                    continue;
                }

                foreach ($allTimes as $time) {
                    DoctorAvailabilitySlot::firstOrCreate(
                        ['doctor_id' => $doctor->id, 'date' => $date, 'start_time' => $time, 'type' => 'video'],
                        ['duration_minutes' => 20, 'clinic_id' => null, 'status' => 'booked']
                    );
                    DoctorAvailabilitySlot::firstOrCreate(
                        ['doctor_id' => $doctor->id, 'date' => $date, 'start_time' => $time, 'type' => 'in_person'],
                        ['duration_minutes' => 30, 'clinic_id' => $clinic?->id, 'status' => 'booked']
                    );
                }
            }

            // Future slots (60 days ahead) — available
            for ($day = 0; $day <= 60; $day++) {
                $date = Carbon::now()->addDays($day)->format('Y-m-d');

                if (Carbon::parse($date)->isFriday()) {
                    continue;
                }

                // Doctors only work some days per week (not every day)
                // Each doctor works 5 out of 6 available days
                $dayOfWeek = Carbon::parse($date)->dayOfWeek;
                if ($dayOfWeek === Carbon::THURSDAY && ($doctor->id % 3 === 0)) {
                    continue; // some doctors off Thursday
                }

                foreach ($morningTimes as $time) {
                    DoctorAvailabilitySlot::firstOrCreate(
                        ['doctor_id' => $doctor->id, 'date' => $date, 'start_time' => $time, 'type' => 'video'],
                        ['duration_minutes' => 20, 'clinic_id' => null, 'status' => 'available']
                    );
                }

                foreach ($afternoonTimes as $time) {
                    DoctorAvailabilitySlot::firstOrCreate(
                        ['doctor_id' => $doctor->id, 'date' => $date, 'start_time' => $time, 'type' => 'in_person'],
                        ['duration_minutes' => 30, 'clinic_id' => $clinic?->id, 'status' => 'available']
                    );
                }
            }
        }
    }
}