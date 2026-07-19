<?php

namespace App\Actions\Patient\Measurements;

use App\Models\UserMeasurement;

use Carbon\Carbon;

class StoreUserMeasurementAction
{
    public function execute(array $data, int $userId): UserMeasurement
    {
        return UserMeasurement::create(array_merge($data, [
            'user_id' => $userId,
            'measured_at' => $data['measured_at'] ?? Carbon::now(),
        ]));
    }

}