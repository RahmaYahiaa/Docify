<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $fillable = ['name'];

    public function patientProfiles()
    {
        return $this->belongsToMany(PatientProfile::class,'patient_allergies');
    }
}
