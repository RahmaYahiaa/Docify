<?php

namespace App\Http\Resources\API\V1\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
{
    protected $bankInfo;

    // بنستقبل الـ bankInfo في الـ constructor
    public function __construct($resource, $bankInfo)
    {
        parent::__construct($resource);
        $this->bankInfo = $bankInfo;
    }

    public function toArray($request): array
    {
        return [
            'amount' => number_format($this->amount, 2),
            'recipient' => [
                'bank_name' => $this->bankInfo['bank_name'] ?? 'N/A',
                'account_type' => 'Checking',
                'last_four_digits' => $this->bankInfo['last4'] ?? '****',
            ],
            'estimated_arrival' => '1-3 Business Days',
            'transaction_id' => $this->transaction_id,
            'status' => 'processed',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}