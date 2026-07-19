<?php

namespace App\Http\Resources\API\V1\Chat;

use App\Http\Resources\BasePaginationResource;
use App\Http\Resources\API\V1\Chat\ConversationResource;

class ConversationCollection extends BasePaginationResource
{
    public $collects = ConversationResource::class;
}
