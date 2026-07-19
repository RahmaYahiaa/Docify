<?php

namespace App\Actions\Patient\Profile\Medication;

use App\Actions\Prescription\GenerateMedicationDosesAction;
use App\Models\Medication;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreMedicationAction
{
    use AsAction;


    public function execute(array $data): PrescriptionItem
    {
        $medicationId = $data['medication_id'] ?? null;

        if (!$medicationId) {
            $medication = Medication::firstOrCreate([
                'name' => ucfirst(trim($data['name'])),
            ]);
            $medicationId = $medication->id;
        }

        $item = PrescriptionItem::create([
            'patient_id'      => Auth::id(),
            'prescription_id' => null,
            'medication_id'   => $medicationId,
            'dosage'          => $data['dosage'],
            'frequency'       => $data['frequency'],
            'duration'        => $data['duration'] ?? 1,
            'start_time'      => $data['start_time'],
        ]);

        app(GenerateMedicationDosesAction::class)->execute($item);

        return $item->load('medication');
    }
}
