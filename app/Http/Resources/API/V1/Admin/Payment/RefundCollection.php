<?php

namespace App\Http\Resources\API\V1\Admin\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RefundCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = RefundResource::class;

    public function toArray(Request $request): array
    {
        return[
            'payments_count' => Payment::payments()->count(),
            'refunds_count'  => Payment::refunds()->count(),
            'data'=>$this->collection,
        ];
    }
}
