<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Medication;
use App\Models\MedicationDose;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $completedAppointments = Appointment::where('status', 'completed')
            ->with(['doctor', 'patient'])
            ->limit(30)
            ->get();

        $dosageOptions = ['500 mg', '250 mg', '1000 mg', '200 mg', '400 mg', '150 mg', '50 mg', '20 mg', '10 mg', '5 mg'];

        $frequencyOptions = [
            'once daily',
            'twice daily',
            'three times daily',
            'every 8 hours',
            'every 12 hours',
            'as needed',
        ];

        $durationOptions = [
            '3 days',
            '5 days',
            '7 days',
            '10 days',
            '14 days',
            '1 month',
            '2 months',
            'continuous',
        ];

        $prescriptionNotes = [
            'Please follow the prescribed dosage and do not exceed it.',
            'Take the medication immediately after meals.',
            'Avoid driving while taking this medication.',
            'If side effects occur, stop the medication and contact me immediately.',
            'Follow-up after two weeks to evaluate treatment response.',
            null,
            null,
        ];

        $medications = Medication::all();

        foreach ($completedAppointments as $appointment) {
            // Skip if prescription already exists for this appointment on the same day
            $alreadyExists = Prescription::where('doctor_id', $appointment->doctor_id)
                ->where('patient_id', $appointment->patient_id)
                ->whereDate('created_at', Carbon::parse($appointment->created_at)->toDateString())
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            // IMPORTANT: Prescription fillable = ['doctor_id', 'patient_id', 'notes', 'diagnosis']
            // 'issued_at' is NOT in fillable — it defaults to current timestamp automatically
            $prescription = Prescription::create([
                'doctor_id'  => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'notes'      => $prescriptionNotes[array_rand($prescriptionNotes)],
            ]);

            $numMeds      = rand(1, 3);
            $selectedMeds = $medications->random(min($numMeds, $medications->count()));

            foreach ($selectedMeds as $medication) {
                $frequency = $frequencyOptions[array_rand($frequencyOptions)];
                $duration  = $durationOptions[array_rand($durationOptions)];
                $dosage    = $dosageOptions[array_rand($dosageOptions)];

                // IMPORTANT: PrescriptionItem fillable includes 'patient_id' (added via migration)
                $item = PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medication_id'   => $medication->id,
                    'patient_id'      => $appointment->patient_id,  // ← required FK
                    'dosage'          => $dosage,
                    'frequency'       => $frequency,
                    'duration'        => $duration,
                ]);

                $dosesPerDay = match (true) {
                    str_contains($frequency, 'three')    => 3,
                    str_contains($frequency, 'twice')    => 2,
                    str_contains($frequency, 'every 8')  => 3,
                    str_contains($frequency, 'every 12') => 2,
                    default                              => 1,
                };

                $durationDays = match (true) {
                    str_contains($duration, '3 days')  => 3,
                    str_contains($duration, '5 days')  => 5,
                    str_contains($duration, '7 days')  => 7,
                    str_contains($duration, '10 days') => 10,
                    str_contains($duration, '14')      => 14,
                    default                            => 7,
                };

                $startDate = now()->subDays(rand(1, 14));
                $doseHours = [8, 14, 20]; // morning, afternoon, evening

                for ($day = 0; $day < min($durationDays, 7); $day++) {
                    for ($dose = 0; $dose < $dosesPerDay; $dose++) {
                        $doseTime = Carbon::parse($startDate)
                            ->addDays($day)
                            ->setHour($doseHours[$dose % 3])
                            ->setMinute(0);

                        MedicationDose::create([
                            'medication_id'        => $medication->id,
                            'prescription_id'      => $prescription->id,
                            'patient_id'           => $appointment->patient_id,
                            'prescription_item_id' => $item->id,
                            'dose_time'            => $doseTime,
                            'amount'               => $dosage,
                            'taken'                => $doseTime->isPast() ? (rand(0, 10) > 2) : false,
                        ]);
                    }
                }
            }
        }
    }
}