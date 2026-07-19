<?php

namespace App\Enums\Payment;

enum PayoutTypeEnum: string
{
    case COMPLETED   = 'completed';
    case LATE_CANCEL = 'late_cancel';
    case NO_SHOW     = 'no_show';

    public function doctorPercentageKey(): string
    {
        return match ($this) {
            self::COMPLETED   => 'doctor_percentage',
            self::LATE_CANCEL => 'late_cancel_doctor_percentage',
            self::NO_SHOW     => 'no_show_doctor_percentage',
        };
    }
}
