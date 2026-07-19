<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use App\Http\Resources\API\V1\Doctor\Appointment\Slot\SlotResource;
use App\Http\Resources\API\V1\Patient\PatientResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Appointment $appointment */
        $appointment = $this->resource;

        return [
            'id' => $this->id, 
            'patient' => $this->whenLoaded('patient', function () {
                return [
                    'user_id'   => $this->patient->id,
                    'full_name' => $this->patient->full_name,
                    'phone'     => $this->patient->phone,

                    'profile' => PatientResource::make(
                        $this->patient->patientProfile
                    )->except(['last_visit', 'next_appointment']),
                ];
            }),

            'slot' => SlotResource::make($this->whenLoaded('slot')),
            'info' => $this->when(
                $request->routeIs('doctor.patients.appointments'),
                [
                    'appointment_id'   => $this->id,
                    'reason_for_visit' => $this->reason_for_visit,
                    'date'             => $this->slot?->date?->format('M d, Y'),
                    'type'             => $this->type,
                    'notes'            => $this->notes,
                ]
            ),
        ];
    }
}