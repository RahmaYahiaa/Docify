<?php

namespace App\Http\Resources\API\V1\Admin\Payment;

use App\Http\Resources\BasePaginationResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = PaymentResource::class;

    public function toArray(Request $request): array
    {
        return[
            'payments_count' => Payment::payments()->count(),
            'refunds_count'  => Payment::refunds()->count(),
            'data'=>$this->collection,
        ];
    }
}
