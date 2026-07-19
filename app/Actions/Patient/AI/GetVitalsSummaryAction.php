<?php

namespace App\Actions\Patient\AI;

use App\Models\UserMeasurement;
use Illuminate\Support\Collection;

class GetVitalsSummaryAction
{
    public function execute(int $userId): Collection
    {

        return UserMeasurement::where('user_id', $userId)
            ->with('type')
            ->latest('measured_at')
            ->get()
            ->unique('measurement_type_id')
            ->values();
    }
}