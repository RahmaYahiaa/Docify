<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class MedicationDose extends Model
{
    protected $fillable = [
        'patient_id',
        'prescription_id',
        'medication_id',
        'prescription_item_id',
        'dose_time',
        'amount',
        'taken',
        'notified_at',
    ];

    protected $casts = [
        'dose_time' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function prescriptionItem()
    {
        return $this->belongsTo(PrescriptionItem::class);
    }

    public function getTargetPatientAttribute()
    {
        if ($this->user) {
            return $this->user;
        }
        return $this->prescriptionItem?->prescription?->patient;
    }
}
