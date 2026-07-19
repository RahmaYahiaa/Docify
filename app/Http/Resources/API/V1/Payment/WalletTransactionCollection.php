<?php

namespace App\Http\Resources\API\V1\Payment;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Request;

class WalletTransactionCollection extends BasePaginationResource
{
    public $collects = WalletTransactionResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->groupBy(fn($item) =>
                $item->created_at->isToday() ? 'Today' :
                ($item->created_at->isYesterday() ? 'Yesterday' : $item->created_at->format('d M, Y'))
            )->map(fn($items, $label) => [
                'label' => $label,
                'items' => $items,
            ])->values()->all(),

            
        ];
    }
}