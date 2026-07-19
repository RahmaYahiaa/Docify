<?php

namespace App\Models;

use App\Enums\Role\UserRoleEnum;
use App\Filters\CreatedAtFilter;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use CreatedAtFilter;

    protected $casts = [
        'role' => UserRoleEnum::class,
    ];
}
