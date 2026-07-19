<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorCommission extends Model
{
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'consultation_fee',
        'commission_rate',
        'commission_amount',
        'type',
        'status',
        'invoiced_at',
        'paid_at',
    ];

    protected $casts = [
        'consultation_fee'  => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'invoiced_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
