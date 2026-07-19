<?php

namespace App\Http\Resources\API\V1\Patient\Appointment;

use App\Http\Resources\API\V1\Doctor\Appointment\DoctorBasicResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentListResource extends JsonResource
{
    public function toArray($request): array
    {
        $slot = $this->slot;

        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'date' => $slot->date->format('Y-m-d'),
            'start_time' => Carbon::parse($slot->getRawOriginal('start_time'))->format('H:i'),
            'doctor' => new DoctorBasicResource($this->doctor),
            'review' => $this->when(
                $this->status->value === 'completed',
                function () {
                    return [
                        'id' => $this->review?->id,
                        'reviewed' => $this->review ? true : false,
                        'rating' => $this->review?->rating,
                        'comment' => $this->review?->comment,
                    ];
                }
            ),
            'clinic_name' => $this->when(
                $this->type->value === 'in_person',
                fn() => $slot->clinic?->name
            ),
        ];
    }
}
