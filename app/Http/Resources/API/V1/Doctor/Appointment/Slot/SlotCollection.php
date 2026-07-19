<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment\Slot;

use App\Http\Resources\API\V1\Doctor\Appointment\Slot\SlotResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SlotCollection extends ResourceCollection
{
 public $collects =SlotResource::class;
}
