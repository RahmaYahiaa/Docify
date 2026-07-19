<?php

namespace App\Http\Resources\API\V1\Patient;

use App\Http\Requests\API\V1\Patient\Measurements\VitalsSummaryResource;
use App\Http\Resources\API\V1\ActivityLogResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;


class PatientDashboardResource extends JsonResource
{
    protected Collection $vitalsSummary;
    protected Collection $activityLogs;

    public function __construct(
        $resource,
        Collection $vitalsSummary,
        Collection $activityLogs
    ) {
        parent::__construct($resource);

        $this->vitalsSummary = $vitalsSummary;
        $this->activityLogs = $activityLogs;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $nextAppointment = $this->next_appointment;
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->full_name,
            ],

            'stats' => [
                'upcoming_count' => $this->upcoming_count ?? 0,
                'active_rx_count' => $this->active_rx_count ?? 0,
            ],

            'next_appointment' => $nextAppointment
                ? [
                    'id' => $nextAppointment->id,
                    'doctor_name' => 'Dr. ' . ($nextAppointment->doctor?->full_name ?? 'Specialist'),
                    'date' => $nextAppointment->slot?->date?->format('Y-m-d'),
                    'start_time' => $nextAppointment->slot?->start_time?->format('H:i'),
                ]
                : null,

            'vitals_summary' => VitalsSummaryResource::collection(
                $this->vitalsSummary
            ),

            'activity_logs' => ActivityLogResource::collection(
                $this->activityLogs
            ),
        ];
    }
}