<?php

namespace App\Enums\Payment;

enum RefundTypeEnum: string
{
    case FULL = 'full';
    case PARTIAL = 'partial';
}
