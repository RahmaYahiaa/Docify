<?php

namespace App\Http\Resources\API\V1\Payment\PaymentMethod;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'type'                => $this->type,
            'bank_name'           => $this->bank_name,
            'account_holder_name' => $this->account_holder_name,

            // بنعرض آخر 4 أرقام بس للأمان
            'account_number'      => '************' . substr($this->account_number, -4),

            // الـ IBAN لو موجود بنعرض آخره برضه
            'iban'                => $this->iban ? 'EG****************' . substr($this->iban, -4) : null,

            'created_at'          => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}