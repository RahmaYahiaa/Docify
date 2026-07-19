<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use App\Http\Resources\API\V1\Doctor\Appointment\ClinicResource;
use App\Http\Resources\BasePaginationResource;

class ClinicCollection extends BasePaginationResource
{
    public $collects = ClinicResource::class;
}
