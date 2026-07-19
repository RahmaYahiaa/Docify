<?php

namespace App\Actions\Role;

use App\Models\Permission;
use App\Models\Role;

class AssignPermissionsToRoleAction
{
    public function execute(Role $role, array $permissionsIds): Role
    {

        $permissionNames = Permission::whereIn('id', $permissionsIds)->pluck('name')->toArray();

        return $role->givePermissionTo($permissionNames);

    }
}
