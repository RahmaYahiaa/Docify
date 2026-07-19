<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment\Slot;

use App\Models\DoctorAvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var DoctorAvailabilitySlot $slot */
        $slot = $this->resource;

        return [
            'id' => $this->whenHas('id', fn() => $slot->id),
            'start_time' => $this->whenHas('start_time',fn() =>Carbon::parse($slot->getRawOriginal('start_time'))->format('h:i A')),
            //   'end_time' => $slot->getEndTimeAttribute(),
            'end_time' => $this->when($slot->start_time, fn() => $slot->end_time),
            'duration' => $this->whenHas('duration_minutes', fn() => $slot->duration_minutes . ' min'),
            'type' => $this->whenHas('type', fn() => $slot->type->value),
            'status' => $this->whenHas('status', fn() => $slot->status->value),
            'date' => $this->whenHas('date', fn() => $slot->date->format('Y-m-d')),
        ];
    }
}
