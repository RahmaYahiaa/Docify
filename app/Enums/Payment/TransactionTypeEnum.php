<?php
namespace App\Enums\Payment;

enum TransactionTypeEnum: string
{
    case EARNING = 'earning';
    case WITHDRAWAL = 'withdrawal';
    case REFUND = 'refund';
    case DEPOSIT = 'deposit';


     
}