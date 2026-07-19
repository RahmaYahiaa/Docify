<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use App\Models\Clinic;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicResource extends JsonResource
{
    public function toArray($request): array
    {


        return [
'id'=>$this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'phone' => $this->phone,
        ];
    }
}
