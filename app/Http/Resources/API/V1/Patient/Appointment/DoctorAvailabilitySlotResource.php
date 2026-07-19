<?php

namespace App\Http\Resources\API\V1\Patient\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DoctorAvailabilitySlotResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'day_name' => $this->date->format('l'),
            'start_time' => Carbon::parse($this->getRawOriginal('start_time'))->format('H:i'),
            'end_time' => $this->end_time,
            'duration_minutes' => $this->duration_minutes,
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'status' => $this->status->value,  // available/booked
            'is_available' => $this->status->isAvailable(),
        ];
    }
}
