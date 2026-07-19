<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use CreatedAtFilter;

    protected $fillable = ['name'];
}
