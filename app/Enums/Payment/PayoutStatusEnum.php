<?php

namespace App\Enums\Payment;

enum PayoutStatusEnum: string
{
    case PENDING    = 'pending';    // Appointment COMPLETED 
    case PROCESSING = 'processing'; // Stripe Disbursement API 
    case PAID       = 'paid';
    case FAILED     = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'Pending',
            self::PROCESSING => 'Processing',
            self::PAID       => 'Paid',
            self::FAILED     => 'Failed',
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isProcessing(): bool
    {
        return $this === self::PROCESSING;
    }

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}
