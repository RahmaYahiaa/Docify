<?php

namespace App\Http\Resources\API\V1\Admin\Permission;

use App\Http\Resources\BasePaginationResource;

class PermissionCollection extends BasePaginationResource
{
    public $collects = PermissionResource::class;
}
