<?php

namespace App\Actions\Prescription;

use App\Events\Prescription\PrescriptionCreated;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;

class CreatePrescriptionAction
{

    public function execute(int $patientId, ?string $notes, ?string $diagnosis, array $medications): Prescription
    {
        return DB::transaction(function () use ($patientId, $notes, $medications, $diagnosis) {


            $prescription = Prescription::create([
                'patient_id' => $patientId,
                'notes' => $notes,
                'diagnosis' => $diagnosis,
            ]);


            foreach ($medications as $medData) {
                $item = $prescription->items()->create([
                    'medication_id' => $medData['medication_id'],
                    'dosage' => $medData['dosage'],
                    'frequency' => $medData['frequency'],
                    'duration' => $medData['duration'],
                    'instruction' => $medData['instruction'] ?? null,
                ]);

                app(GenerateMedicationDosesAction::class)->execute($item);
            }

            activity()
                ->performedOn($prescription)
                ->causedBy(auth()->user())
                ->withProperties([
                    'patient_name' => $prescription->patient?->full_name,
                    'doctor_name' => $prescription->doctor?->full_name,
                    'status' => 'created',
                    'message' => "Prescription created by Dr. {$prescription->doctor?->full_name}",
                ])
                ->log('Prescription Created');

            event(new PrescriptionCreated($prescription));

            return $prescription;
        });
    }
}
