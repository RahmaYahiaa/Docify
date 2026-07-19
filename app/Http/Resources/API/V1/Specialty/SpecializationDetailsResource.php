<?php

namespace App\Http\Resources\API\V1\Specialty;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
         'specialty' => new SpecializationResource($this->resource),
            'description' => $this->description,
            'status'=>$this->status,
            'image'=>$this->getFirstMediaUrl('specialty_icon'),
            'doctors_count' => $this->doctor_profiles_count ?? 0,
        ];
    }
}
