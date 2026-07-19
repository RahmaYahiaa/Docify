<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
 protected $fillable = [
        'user_id', 'type', 'bank_name', 'account_holder_name', 'account_number', 'iban'
    ];


    protected $casts = [
        'account_number' => 'encrypted',
        'iban' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
