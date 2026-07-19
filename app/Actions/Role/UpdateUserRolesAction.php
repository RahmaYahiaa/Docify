<?php

namespace App\Actions\Role;

use App\Models\Role;
use App\Models\User\User;

class UpdateUserRolesAction
{
    public function execute(User $user, array $roleIds): User
    {

        $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        $user->syncRoles($roleNames);

        return $user;
    }
}
