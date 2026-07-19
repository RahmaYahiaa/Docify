<?php

namespace App\Http\Controllers\API\V1\Patient;

use App\Actions\Patient\AI\GetVitalsSummaryAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Patient\PatientDashboardResource;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class PatientDashboardController extends Controller
{
    public function dashboard(Request $request, GetVitalsSummaryAction $getVitalsSummaryAction)
    {
        $patient = $request->user();

        $patient->loadCount([
            'upcomingAppointments as upcoming_count',
            'patientPrescriptions as active_rx_count'
        ]);

        $vitalsSummary = $getVitalsSummaryAction->execute($patient->id);

        $activityLogs = Activity::where('causer_id', $patient->id)
            ->where('causer_type', get_class($patient))
            ->latest()
            ->take(10)
            ->get();

        return new PatientDashboardResource(
            $patient,
            $vitalsSummary,
            $activityLogs,
        );
    }
}
