<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorBasicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'phone' => $this->phone,
            'specialty' => $this->whenLoaded('doctorProfile', fn() => $this->doctorProfile?->specialization?->name),
            'profile_picture' => $this->whenLoaded('doctorProfile', fn() => $this->doctorProfile?->getFirstMediaUrl('profile_picture') ?? '/images/default-doctor.jpg')
        ];
    }
}
