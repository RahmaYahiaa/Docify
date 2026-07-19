<?php

namespace App\Http\Resources\API\V1\Assistant;

use App\Models\Appointment;
use App\Enums\Appointment\AppointmentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $doctor_id = $this->doctor_id;


        $nextApp = Appointment::forDoctor($doctor_id)
            ->todayAppointments()
            ->nextUpcoming()
            ->with(['slot', 'patient'])
            ->first();


        $todayQuery = Appointment::forDoctor($doctor_id)->todayAppointments();

        return [
            'greeting'       => $this->getGreeting(),
            'assistant_name' => $this->full_name,
                'linked_doctor'  => $this->doctor ? 'Dr. ' . $this->doctor->full_name : 'No doctor linked',
            'today_date'     => now()->format('l, F j'),
            'stats' => [
                'total_today' => (int) $todayQuery->count(),
                'upcoming'    => (int) (clone $todayQuery)->where('status', AppointmentStatusEnum::CONFIRMED)->count(),
                'completed'   => (int) (clone $todayQuery)->where('status', AppointmentStatusEnum::COMPLETED)->count(),
            ],
            'next_session' => $nextApp ? [
                'time'         => $nextApp->slot->start_time->format('h:i A'),
                'type'         => $nextApp->type->value,
                'patient_name' => $nextApp->patient->full_name ?? 'N/A',
            ] : [
                'time'         => 'No more slots',
                'type'         => null,
                'patient_name' => null,
            ],
        ];
    }
}