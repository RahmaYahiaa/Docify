<?php
namespace App\Actions\User;

use App\Models\Role;
use App\Models\User\User;

class AssignRolesToUserAction
{
    public function execute(User $user, int $roleId): User
    {

        $roleName = Role::where('id', $roleId)->pluck('name')->first();

        return $user->assignRole($roleName);
    }
}

