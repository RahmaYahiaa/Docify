<?php

namespace App\Http\Resources\API\V1\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'amount' => $this->amount,
            'consultation_fee' => $this->consultation_fee,
            'platform_fee' => $this->platform_fee,
            'currency' => $this->currency,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'paid_at' => $this->paid_at?->format('M d, Y \a\t h:i A'),
            'refund_amount' => $this->when(
                $this->isRefunded(),
                fn() => $this->refund_amount
            ),
            'refunded_at' => $this->when(
                $this->isRefunded(),
                fn() => $this->refunded_at?->format('M d, Y \a\t h:i A')
            ),
        ];
    }
}
