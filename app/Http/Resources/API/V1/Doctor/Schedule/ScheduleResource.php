<?php

namespace App\Http\Resources\API\V1\Doctor\Schedule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'patient' => $this->whenLoaded('patient', function() {
                $fullName = "{$this->patient->first_name} {$this->patient->last_name}";
                return [
                    'id'       => $this->patient->id,
                    'name'     => $fullName,
                    'avatar'   => $this->patient->getProfilePicture(),
                ];
            }),

            'slot' => $this->whenLoaded('slot', fn() => [
                'start_time' => Carbon::parse($this->slot->start_time)->format('H:i'),
                'end_time' => Carbon::parse($this->slot->end_time)->format('H:i'),
                'duration'   => $this->slot->duration_minutes . ' min',
            ]),

            'type'       => $this->type,

            'status'     => $this->status,
        ];
    }


}