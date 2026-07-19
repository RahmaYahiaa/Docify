<?php

namespace App\Models;

use App\Enums\Payment\RefundTypeEnum;
use App\Enums\Payment\PaymentStatusEnum;
use App\Enums\Payment\PayoutStatusEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'amount',
        'consultation_fee',
        'platform_fee',
        'doctor_amount',
        'actual_payout_amount',
        'platform_amount',
        'currency',
        'payment_method',
        'stripe_payment_intent_id',
        'status',
        'payout_status',
        'payout_transaction_id',
        'paid_at',
        'payout_at',
        'refunded_at',
        'refund_amount',
        'refund_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'consultation_fee' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'doctor_amount' => 'decimal:2',
        'actual_payout_amount' => 'decimal:2',
        'platform_amount'  => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'status' => PaymentStatusEnum::class,
        'payout_status' => PayoutStatusEnum::class,
        'paid_at' => 'datetime',
        'payout_at' => 'datetime',
        'refunded_at' => 'datetime',
        'refund_type' => RefundTypeEnum::class,
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function isPaid(): bool
    {
        return $this->status->isPaid();
    }

    public function isRefunded(): bool
    {
        return $this->status->isRefunded();
    }
    //  Doctor doesn't receive payout
    public function isPendingPayout(): bool
    {
        return $this->payout_status->isPending();
    }
    //  Doctor receive payout
    public function isPayoutCompleted(): bool
    {
        return $this->payout_status->isPaid();
    }
    // refundable if paid and not refunded yet
    public function isRefundable(): bool
    {
        return $this->status->isPaid() && !$this->status->isRefunded();
    }
    // payoutable if paid and payout not completed yet
    public function isPayoutable(): bool
    {
        return $this->status->isPaid() && $this->payout_status->isPending();
    }
    public static function getTodayDoctorEarnings($doctorId)
    {
        return self::where('doctor_id', $doctorId)
            ->where(function ($q) {
                $q->where('payout_status', 'paid')
                    ->orWhere('payout_status', 'PAID');
            })
            ->whereDate('payout_at', \Carbon\Carbon::today())
            ->selectRaw('
            COUNT(*) as count,
            SUM(actual_payout_amount) as amount
        ')
            ->first();
    }

    public function scopePayments($query)
    {
        return $query->whereNull('refunded_at');
    }

    public function scopeRefunds($query)
    {
        return $query->whereNotNull('refunded_at');
    }
}
