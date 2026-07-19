<?php

namespace App\Http\Controllers\API\V1\Patient\Measurements;

use App\Actions\Patient\AI\GetMeasurementPredictionAction;
use App\Actions\Patient\AI\GetVitalsSummaryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Measurements\PredictMeasurementRequest;
use App\Http\Requests\API\V1\Patient\Measurements\VitalsSummaryResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class MeasurementPredictionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private GetMeasurementPredictionAction $action
    ) {}

    public function predict(PredictMeasurementRequest $request): JsonResponse
    {

  $result = $this->action->execute( auth()->id(), $request->validated('measure_type_id'), $request->validated('days'));

        return $this->ok($result);
    }

    public function getSummary(GetVitalsSummaryAction $action)
{
    $summary = $action->execute(auth()->id());

    return $this->ok(data:  VitalsSummaryResource::collection($summary) );
}
}