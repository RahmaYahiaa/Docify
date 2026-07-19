<?php

namespace App\Models;

use App\Enums\Appointment\AppointmentTypeEnum;
use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class DoctorAvailabilitySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'duration_minutes',
        'type',
        'clinic_id',
        'status',
        'locked_until',
    ];

    protected $casts = [
        'date' => 'date',
 
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'type' => AppointmentTypeEnum::class,
        'status' => AvailabilitySlotStatusEnum::class,
        'locked_until' => 'datetime',
    ];


    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class, 'slot_id');
    }


    public function getEndTimeAttribute(): string
    {
        return Carbon::parse(
            $this->date->format('Y-m-d') . ' ' . $this->getRawOriginal('start_time')
        )
            ->addMinutes($this->duration_minutes)
            ->format('H:i');
    }

    public function isAvailable(): bool
    {
        return $this->status->isAvailable();
    }

    public function isLocked(): bool
    {
        return $this->status->isLocked();
    }

    public function isBooked(): bool
    {
        return $this->status->isBooked();
    }
}
