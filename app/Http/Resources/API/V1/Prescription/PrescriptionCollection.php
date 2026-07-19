<?php

namespace App\Http\Resources\API\V1\Prescription;

use App\Http\Resources\BasePaginationResource;

class PrescriptionCollection extends BasePaginationResource
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
{
    public $collects = PrescriptionResource::class;
}
