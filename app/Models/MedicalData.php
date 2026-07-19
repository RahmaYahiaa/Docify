<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalData extends Model
{
    protected $fillable = ['blood_type', 'height', 'weight'];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class);
    }
}
