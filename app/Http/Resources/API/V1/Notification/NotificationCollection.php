<?php

namespace App\Http\Resources\API\V1\Notification;


use App\Http\Resources\BasePaginationResource;

class NotificationCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = NotificationResource::class;
}
