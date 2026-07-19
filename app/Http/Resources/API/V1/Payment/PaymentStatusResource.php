<?php

namespace App\Http\Resources\API\V1\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status' => $this->resource['status'],
            'appointment_id' => $this->resource['appointment_id'],
        ];
    }
}
