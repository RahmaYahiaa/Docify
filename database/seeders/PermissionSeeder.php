<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Permissions\PermissionRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = PermissionRegistry::all();
        $admin = Role::where('name', UserRoleEnum::ADMIN->value)->first();

        foreach ($permissions as $permission) {

            $permission = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => Config::get('auth.defaults.guard'),
            ]);

            $admin->givePermissionTo($permission);
        }
    }
}