<?php

namespace App\Http\Resources\API\V1\User;

use App\Http\Resources\BasePaginationResource;

class UserCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = UserResource::class;
}
