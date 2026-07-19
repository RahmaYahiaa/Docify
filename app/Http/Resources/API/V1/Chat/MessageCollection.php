<?php

namespace App\Http\Resources\API\V1\Chat;

use App\Http\Resources\BasePaginationResource;

class MessageCollection extends BasePaginationResource
{
    public $collects = MessageResource::class;
}
