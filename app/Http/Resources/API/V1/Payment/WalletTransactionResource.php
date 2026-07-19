<?php

namespace App\Http\Resources\API\V1\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     return [
            'id'          => $this->id,
            'amount'      => (float) $this->amount,
            'type'        => $this->type,
            'status'      => $this->status,
            'description' => $this->description,
            'date'        => $this->created_at->format('Y-m-d H:i'),
            'human_date'  => $this->created_at->diffForHumans(),
            
        ];
    }
}
