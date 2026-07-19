<?php

namespace App\Http\Resources\API\V1\Doctor\Schedule;

use App\Http\Resources\API\V1\Doctor\Schedule\ScheduleResource;
use App\Http\Resources\BasePaginationResource;

class ScheduleCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

  public $collects = ScheduleResource::class;

}
