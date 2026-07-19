<?php

namespace App\Http\Resources\API\V1\Patient\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentInitiationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'cache_key' => $this->resource['cache_key'],
            'client_secret' => $this->resource['client_secret'],
            'expires_at' => $this->resource['expires_at'],
            'amount' => $this->resource['amount'],
            'cost_breakdown' => $this->resource['cost_breakdown'],
        ];
    }
}
