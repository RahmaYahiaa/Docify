<?php

namespace App\Actions\Patient\Profile\Medication;

use App\Actions\Prescription\GenerateMedicationDosesAction;
use App\Models\Medication;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateMedicationAction
{
    use AsAction;

    public function execute(PrescriptionItem $item, array $data): PrescriptionItem
    {
        return DB::transaction(function () use ($item, $data) {

            if (isset($data['name']) && $data['name'] !== $item->medication->name) {
                $medication = Medication::firstOrCreate([
                    'name' => trim($data['name']),
                ]);

                $item->medication_id = $medication->id;
            }

            $item->fill([
                'dosage'     => $data['dosage']     ?? $item->dosage,
                'frequency'  => $data['frequency']  ?? $item->frequency,
                'duration'   => $data['duration']   ?? $item->duration,
                'start_time' => $data['start_time'] ?? $item->start_time,
            ]);

            $shouldRegenerate = $item->isDirty([
                'dosage',
                'frequency',
                'duration',
                'start_time',
            ]);

            $item->save();

            if ($shouldRegenerate) {
                // delete future doses only
                $item->doses()->where('dose_time', '>', now())->delete();

                app(GenerateMedicationDosesAction::class)->execute($item, true);
            }

            return $item->load('medication');
        });
    }
}
