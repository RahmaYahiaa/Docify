<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment\Slot;

use App\Http\Resources\BasePaginationResource;

class DoctorSlotCollection extends BasePaginationResource
{
    public $collects = DoctorSlotResource::class;
}