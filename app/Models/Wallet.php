<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
  protected $fillable = ['doctor_id', 'balance'];


public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}


public function transactions()
{
    return $this->hasMany(WalletTransaction::class);
}
}
