<?php

namespace App\Enums\Payment;

enum PaymentStatusEnum: string
{
    case PENDING  = 'pending';
    case PAID     = 'paid';
    case FAILED   = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'Pending',
            self::PAID     => 'Paid',
            self::FAILED   => 'Failed',
            self::REFUNDED => 'Refunded',
        };
    }

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function isRefunded(): bool
    {
        return $this === self::REFUNDED;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}
