<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class DeletePermissionAction
{
    public function execute(Permission $permission): bool
    {
        return $permission->delete();
    }
}
