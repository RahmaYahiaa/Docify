<?php

namespace App\Http\Resources\API\V1\Assistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'patient' => [
                'name' => $this->whenLoaded('patient', function() {
                    return $this->patient->full_name;
                }),
                'image' => $this->whenLoaded('patient', function() {
                    return $this->patient->getProfilePicture();
                }),
            ],

            'time' => $this->whenLoaded('slot', function() {
                return Carbon::parse($this->slot->getRawOriginal('start_time'))->format('h:i A');
            }),
            'status' => $this->status->value ?? $this->status,
            'type'   => $this->type->value ?? $this->type,
            // 'payment' => [
            //     'is_paid' => $this->payment()->exists(),
            //     'method'  => 'Card',
            // ],


        ];
    }
}