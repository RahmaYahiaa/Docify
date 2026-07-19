<?php

namespace App\Http\Resources\API\V1\Patient\ChronicCondition;

use App\Http\Resources\BasePaginationResource;
 
class ChronicConditionCollection extends BasePaginationResource
{
public $collects=ChronicConditionResource::class;
}
