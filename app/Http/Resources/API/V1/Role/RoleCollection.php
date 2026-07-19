<?php

namespace App\Http\Resources\API\V1\Role;

use App\Http\Resources\BasePaginationResource;

class RoleCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = RoleResource::class;
}
