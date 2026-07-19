<?php

namespace App\Http\Resources\API\V1\Specialty;

use Illuminate\Http\Request;
use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\API\V1\Specialty\SpecializationResource;

class SpecializationCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
     public $collects = SpecializationResource::class;
}
