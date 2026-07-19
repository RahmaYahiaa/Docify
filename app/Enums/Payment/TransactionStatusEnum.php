<?php
namespace App\Enums\Payment;

enum TransactionStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}