<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use App\Http\Resources\API\V1\Doctor\Appointment\DoctorListResource ;
use App\Http\Resources\BasePaginationResource;


class DoctorListCollection extends BasePaginationResource
{
    public $collects = DoctorListResource::class;
}