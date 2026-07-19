<?php

namespace App\Http\Resources\API\V1\Patient\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'url'  => $this->url, 
            'data' => [
                'first_name' => $this->user->first_name,
                'last_name'  => $this->user->last_name,
                'gender'     => $this->user->patientProfile?->gender,
                'date_of_birth' => $this->user->patientProfile?->date_of_birth,
                'emergency_contact' => $this->user->patientProfile?->emergency_contact,
            ]
        ];
    }
}
