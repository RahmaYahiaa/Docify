<?php

namespace App\Models;

use App\Enums\Payment\TransactionStatusEnum;
use App\Enums\Payment\TransactionTypeEnum;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = ['wallet_id', 'amount', 'type', 'status', 'description'];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
    protected $casts = [
    'type' => TransactionTypeEnum::class,
    'status' =>  TransactionStatusEnum::class,
];
}