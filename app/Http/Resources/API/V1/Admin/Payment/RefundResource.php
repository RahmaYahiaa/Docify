<?php

namespace App\Http\Resources\API\V1\Admin\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'refund_id' => $this->id,
            'transaction_id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'patient' => $this->patient?->full_name,
            'patient_id' => $this->patient_id,
            'refund_amount' => $this->refund_amount,
            'date' => $this->refunded_at?->format('M d, Y'),
            'time' => $this->refunded_at?->format('h:i A'),
            'refund_type' => $this->refund_type,
        ];
    }
}
