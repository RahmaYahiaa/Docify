<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChronicCondition extends Model
{
    protected $fillable = ['name'];

    public function patientProfiles()
    {
        return $this->belongsToMany(PatientProfile::class,'patient_chronic_conditions');
    }
}
