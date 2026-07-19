<?php

namespace App\Actions\Patient\Profile\Medication;

use App\Models\Medication;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteMedicationAction
{
    use AsAction;

    public function execute(PrescriptionItem $item): void
    {
        DB::transaction(function () use ($item) {
            $medicationId = $item->medication_id;

            $item->doses()->delete();

            $item->delete();

            $isUsed = PrescriptionItem::where('medication_id', $medicationId)->exists();

            if (!$isUsed) {
                Medication::where('id', $medicationId)->delete();
            }
        });
    }
}
