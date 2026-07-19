<?php

namespace App\Http\Resources\API\V1\Admin\Appointment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'appointment_id' => $this->id,

            'doctor' => $this->doctor?->full_name,
            'patient' => $this->patient?->full_name,

            'specialty' => $this->doctor?->specialization?->name,
            
            'date' => $this->created_at?->format('M d, Y'),
            'time' => $this->created_at?->format('h:i A'),

            'type' => $this->type->value,

            'status' => $this->status,
            
            'amount' => $this->payment?->amount,
            'payment_status' => $this->payment?->status,
        ];
    }
}
