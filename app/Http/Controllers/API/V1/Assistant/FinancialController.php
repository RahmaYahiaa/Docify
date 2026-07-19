<?php

namespace App\Http\Controllers\API\V1\Assistant;

use App\Actions\Assistant\GetTodayAssistantFinancialSummaryAction;
use App\Actions\Assistant\GetTodayFinancialDashboardAction;
use App\Actions\Assistant\MarkAppointmentAsPaidAction;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Appointment\AppointmentTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assistant\MarkPaidRequest;
use App\Http\Resources\API\V1\Assistant\AppointmentResource;
use App\Http\Resources\API\V1\Assistant\FinancialSummaryResource;
use App\Http\Resources\API\V1\Assistant\TodayFinancialSummaryResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class FinancialController extends Controller
{

 public function todayCashSummary(GetTodayAssistantFinancialSummaryAction $action): JsonResponse
    {

        $summaryData = $action->execute(auth()->user());


        return $this->ok( data: new TodayFinancialSummaryResource($summaryData)  );
    }
    public function markAsPaid( Appointment $appointment, MarkPaidRequest $request, MarkAppointmentAsPaidAction $action): JsonResponse {

    $action->execute($appointment);

    return $this->ok(message: __('messages.payment_confirmed'));
}
public function summary(GetTodayFinancialDashboardAction $action): JsonResponse
    {

        $summaryData = $action->execute(auth()->user());
        return $this->ok(  data: new FinancialSummaryResource($summaryData)  );
    }
}