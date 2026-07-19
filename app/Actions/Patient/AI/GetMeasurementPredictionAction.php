<?php

namespace App\Actions\Patient\AI;

use App\Models\UserMeasurement;
use App\Services\AI\MachineLearningService;
use App\Exceptions\UnsupportedMeasureTypeException;
use App\Exceptions\InsufficientDataException;

class GetMeasurementPredictionAction
{
    public function __construct(
        private MachineLearningService $mlService
    ) {}

    public function execute(int $userId, int $measureTypeId, int $days)
    {
        $typesMap = [
            1 => 'bp_systolic',
            2 => 'heart_rate',
            3 => 'glucose',
            4 => 'temp_c',
        ];

        $measureType = $typesMap[$measureTypeId] ?? null;

        if (!$measureType) {
            throw new UnsupportedMeasureTypeException(__('messages.unsupported_type'));
        }

        $measurements = UserMeasurement::where('user_id', $userId)
            ->where('measurement_type_id', $measureTypeId)
            ->latest('measured_at')
            ->take($days)
            ->get();

        if ($measurements->count() < 4) {
            throw new InsufficientDataException();
        }

        $maxRecord = $measurements->max('value');
        $minRecord = $measurements->min('value');

        $historicalData = $measurements->reverse()->map(function ($m) {
            return [
                'value' => (float) $m->value,
                'date'  => $m->measured_at->toIso8601String(),
            ];
        })->values()->toArray();

        $payload = [
            "measure_type"    => (string) $measureType,
            "horizon_days"    => 3,
            "value"           => (float) $measurements->first()->value,
            "historical_data" => $historicalData,
        ];

        $prediction = $this->mlService->predict($payload);

        return [
            'statistics' => [
                'highest_value' => (float) $maxRecord,
                'lowest_value'  => (float) $minRecord,
            ],
            'prediction' => $prediction
        ];
    }
}