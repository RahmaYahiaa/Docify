<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Admin\Payment\PaymentCollection;
use App\Http\Resources\API\V1\Admin\Payment\RefundCollection;
use App\Models\Payment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentController extends Controller
{
    public function payment()
    {
        $payments = QueryBuilder::for(Payment::class)
            ->with(['patient', 'doctor'])
            ->whereNull('refunded_at')

            ->allowedFilters([
                AllowedFilter::partial('status'),
                AllowedFilter::partial('payment_method'),

                AllowedFilter::callback('search', function ($query, $value) {
                    $query->whereHas('patient', function ($q) use ($value) {
                        $q->where('full_name', 'like', "%{$value}%");
                    });
                }),
            ])

            ->latest()
            ->paginate(10);
        return new PaymentCollection($payments);    
    }

    public function refund()
    {
        $refunds = QueryBuilder::for(Payment::class)
            ->with(['patient', 'doctor'])
            ->whereNotNull('refunded_at')

            ->allowedFilters([
                AllowedFilter::partial('status'),
                AllowedFilter::partial('refund_type'),
                AllowedFilter::partial('refund_amount'),

                AllowedFilter::callback('search', function ($query, $value) {
                    $query->whereHas('patient', function ($q) use ($value) {
                        $q->where('full_name', 'like', "%{$value}%");
                    });
                }),
            ])

            ->latest()
            ->paginate(10);
        return new RefundCollection($refunds);    
    }
}
