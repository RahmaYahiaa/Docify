<?php

namespace App\Http\Resources\API\V1\Patient\Allergy;

use App\Http\Resources\BasePaginationResource;
 
class AllergyCollection extends BasePaginationResource
{
     public $collects=AllergyResource::class;
}
