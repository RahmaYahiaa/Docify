<?php

namespace App\Http\Resources\API\V1\Patient\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'blood_type' => $this->patientProfile?->medicalData?->blood_type,
            'height'     => $this->patientProfile?->medicalData?->height,
            'weight'     => $this->patientProfile?->medicalData?->weight,

            'chronic_conditions' => $this->patientProfile
                ? $this->patientProfile->chronicConditions->pluck('name')->all()
                : [],

            'allergies' => $this->patientProfile
                ? $this->patientProfile->allergies->pluck('name')->all()
                : [],
        ];
    }
}
