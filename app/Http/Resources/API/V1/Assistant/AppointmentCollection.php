<?php

namespace App\Http\Resources\API\V1\Assistant;

use App\Http\Resources\BasePaginationResource;
 
class AppointmentCollection extends BasePaginationResource
{
     public $collects=AppointmentResource::class;
}
