<?php

namespace App\Actions\Prescription;

use Carbon\Carbon;
use App\Models\MedicationDose;
use App\Models\PrescriptionItem;
use App\Enums\Patient\MedicationFrequencyEnum;

class GenerateMedicationDosesAction
{
    public function execute(PrescriptionItem $item, bool $isUpdate = false): void
    {
        $frequency = MedicationFrequencyEnum::resolve($item->frequency);

        $duration = max((int) $item->duration, 1);

        $intervalHours = 24 / $frequency;

        $totalDoses = $frequency * $duration;

        $existingDoses = $isUpdate ? $item->doses()->count() : 0;

        $remainingDoses = $isUpdate
            ? max($totalDoses - $existingDoses, 0)
            : $totalDoses;

        if ($remainingDoses <= 0) {
            return;
        }

        $lastDose = $isUpdate
            ? $item->doses()->orderByDesc('dose_time')->first()
            : null;

        $startTime = $lastDose
            ? Carbon::parse($lastDose->dose_time)->addHours($intervalHours)
            : Carbon::parse($item->start_time);

        $doses = [];

        for ($i = 0; $i < $remainingDoses; $i++) {
            $doses[] = [
                'prescription_id'      => $item->prescription_id,
                'medication_id'        => $item->medication_id,
                'prescription_item_id' => $item->id,
                'patient_id'           => $item->patient_id,
                'dose_time'            => $startTime->copy()->addHours($i * $intervalHours),
                'amount'               => $item->dosage,
                'taken'                => false,
                'created_at'           => now(),
                'updated_at'           => now(),
            ];
        }

        MedicationDose::insert($doses);
    }
}
