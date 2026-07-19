<?php

namespace App\Http\Resources\API\V1\Admin\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'patient' => $this->patient?->full_name,
            'patient_id' => $this->patient_id,
            'doctor' => $this->doctor?->full_name,
            'status' => $this->status?->value,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'date' => $this->paid_at?->format('M d, Y'),
            'time' => $this->paid_at?->format('h:i A'),
        ];
    }
}
