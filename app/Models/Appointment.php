<?php

namespace App\Models;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Appointment\AppointmentTypeEnum;
use App\Enums\Appointment\CancelReasonEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'slot_id',
        'type',
        'status',
        'reason_for_visit',
        'notes',
        'confirmed_at',
        'cancelled_at',
        'cancel_reason',
        'cancel_notes',
        'cancelled_by',
        'is_paid_cash',
        'reminder_sent_at',
    'cash_collected_at',

    ];

    protected $casts = [
        'type' => AppointmentTypeEnum::class,
        'status' => AppointmentStatusEnum::class,
        'cancel_reason' => CancelReasonEnum::class,
        'confirmed_at' => 'datetime',
        'start_time' => 'datetime',
        'date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(DoctorAvailabilitySlot::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isFinal(): bool
    {
        return $this->status->isFinal();
    }

    public function isCancellable(): bool
    {
        return $this->status->canCancel();
    }

    public function isReschedulable(): bool
    {
        if (!$this->status->canReschedule()) {
            return false;
        }

        return $this->slot->date
            ->setTimeFromTimeString($this->slot->getRawOriginal('start_time'))
            ->isAfter(now()->addHours(24));
    }

    public function requiresPayment(): bool
    {
        return $this->type->requiresPayment();
    }
    // Full refund if cancellation is done before 24h of the appointment time
    public function isRefundable(): bool
    {
        if (!$this->requiresPayment()) {
            return false;
        }
        return $this->slot->date
            ->setTimeFromTimeString($this->slot->getRawOriginal('start_time'))
            ->isAfter(now()->addHours(24));
    }

    public function scopeConfirmed($query)
    {
        return $query->where(
            'status', AppointmentStatusEnum::CONFIRMED
        );
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function scopeUpcoming($query)
{
    return $query->whereHas('slot', function ($q) {
        $q->where('date', '>', now()->toDateString());
    });
}

public function scopeForDoctor($query, $doctorId)
{
    return $query->where('doctor_id', $doctorId);
}

public function scopeTodayAppointments($query)
{
    return $query->whereHas('slot', function ($q) {
        $q->whereDate('date', now()->today())
         ->orderBy('start_time', 'asc');
    });
}

public function scopeNextUpcoming($query)
{
    return $query->todayAppointments()
    ->where('status', AppointmentStatusEnum::CONFIRMED)
        ->whereHas('slot', function ($q) {
            $q->where('start_time', '>', now()->format('H:i:s'))
              ->orderBy('start_time', 'asc');
        });
}

public function scopeWithStatus($query, $status)
{
    return $query->where('status', $status);
}


public function scopeTodayCash($query, $doctorId) {
    return $query->where('doctor_id', $doctorId)
                 ->where('type', AppointmentTypeEnum::IN_PERSON)
                 ->todayAppointments();
}


 



}
