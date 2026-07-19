<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class PrescriptionRequest extends Model
{
    protected $fillable = [
        'prescription_id',
        'patient_id',
        'doctor_id',
        'note',
        'status'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class);
    }
}
