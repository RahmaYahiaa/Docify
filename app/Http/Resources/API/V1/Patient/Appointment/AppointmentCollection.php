<?php

namespace App\Http\Resources\API\V1\Patient\Appointment;

use App\Http\Resources\BasePaginationResource;

class AppointmentCollection extends BasePaginationResource
{
    public $collects = AppointmentListResource::class;
}
