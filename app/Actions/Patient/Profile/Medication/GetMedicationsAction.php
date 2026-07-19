<?php

namespace App\Actions\Patient\Profile\Medication;

use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMedicationsAction
{
    use AsAction;

    public function execute()
    {
        $user = Auth::id();

        return PrescriptionItem::with(['medication', 'doses'])
            ->where('patient_id', $user) 
            ->get();
    }
}
