<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = User::role('patient')->get();

        $visitReasons = [
    'Routine check-up and general examination',
    'Chest pain and shortness of breath',
    'Persistent headache and difficulty concentrating',
    'High blood pressure follow-up and medication adjustment',
    'Diabetes follow-up and blood test review',
    'Back and joint pain',
    'Skin allergy and rash',
    'Routine eye examination',
    'Sore throat and nasal congestion',
    'Anxiety, insomnia, and sleep disturbances',
    'Fever and persistent cold symptoms',
    'Post-surgery follow-up',
    'Pre-travel medical examination',
    'Stomach pain and indigestion',
    'Consultation to discuss laboratory and imaging results',
];

$notes = [
    'The patient has been experiencing these symptoms for two weeks.',
    'Appointment scheduled following an emergency department visit.',
    'Review of recent laboratory test results.',
    null,
    null,
    'The patient is taking long-term medications and requested a medication review.',
    null,
    'Seeking a second medical opinion.',
    null,
];

        // Past appointments — completed/cancelled
        $pastSlots = DoctorAvailabilitySlot::where('status', 'booked')
            ->where('date', '<', now()->format('Y-m-d'))
            ->inRandomOrder()
            ->limit(60)
            ->get();

        foreach ($pastSlots as $i => $slot) {
            $patient = $patients->random();
            $status  = $i % 7 === 0 ? 'cancelled' : ($i % 10 === 0 ? 'no_show' : 'completed');

            Appointment::firstOrCreate(
                ['slot_id' => $slot->id],
                [
                    'patient_id'       => $patient->id,
                    'doctor_id'        => $slot->doctor_id,
                    'type'             => $slot->type,
                    'status'           => $status,
                    'reason_for_visit' => $visitReasons[array_rand($visitReasons)],
                    'notes'            => $notes[array_rand($notes)],
                ]
            );
        }

        // Upcoming confirmed appointments
        $upcomingSlots = DoctorAvailabilitySlot::where('status', 'available')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('date', '<=', now()->addDays(14)->format('Y-m-d'))
            ->inRandomOrder()
            ->limit(40)
            ->get();

        foreach ($upcomingSlots as $slot) {
            $patient = $patients->random();

            Appointment::firstOrCreate(
                ['slot_id' => $slot->id],
                [
                    'patient_id'       => $patient->id,
                    'doctor_id'        => $slot->doctor_id,
                    'type'             => $slot->type,
                    'status'           => 'confirmed',
                    'reason_for_visit' => $visitReasons[array_rand($visitReasons)],
                    'notes'            => $notes[array_rand($notes)],
                ]
            );

            $slot->update(['status' => 'booked']);
        }
    }
}