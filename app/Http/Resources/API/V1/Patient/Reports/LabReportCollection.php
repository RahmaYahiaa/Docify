<?php

namespace App\Http\Resources\API\V1\Patient\Reports;

use App\Http\Resources\BasePaginationResource;
 

class LabReportCollection extends BasePaginationResource
{
public $collects=LabReportResource::class;
}
