<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    protected $fillable = ['prescription_id', 'patient_id', 'medication_id', 'dosage', 'frequency', 'duration','instruction','start_time'];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function doses()
    {
        return $this->hasMany(MedicationDose::class, 'prescription_item_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
