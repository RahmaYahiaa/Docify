<?php

namespace App\Http\Resources\API\V1\Patient\Appointment;

use App\Http\Resources\API\V1\Patient\Appointment\DoctorAvailabilitySlotResource;
use App\Http\Resources\BasePaginationResource;

class DoctorAvailabilitySlotCollection extends BasePaginationResource
{
    public  $collects = DoctorAvailabilitySlotResource::class;
}