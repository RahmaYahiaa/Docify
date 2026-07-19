<?php

namespace App\Http\Resources\API\V1\Review;

use App\Http\Resources\BasePaginationResource;

class ReviewCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public $collects = ReviewResource::class;
}
