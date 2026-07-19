<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementType extends Model
{
  protected $fillable = ['name', 'unit'];

 
    public function userMeasurements()
    {
        return $this->hasMany(UserMeasurement::class);
    }
}
