<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserRoleEnum::cases() as $roleEnum) {
            Role::firstOrCreate([
                'name' => $roleEnum->value,
                'guard_name' => Config::get('auth.defaults.guard'),
            ]);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'phone'      => '01000000000',
                'status'     => 'active',
                'password'   => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);

        $assistant = User::updateOrCreate(
            ['email' => 'assistant@gmail.com'],
            [
                'first_name' => 'Assistant',
                'last_name'  => 'User',
                'phone'      => '01000000001',
                'status'     => 'active',
                'password'   => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $assistant->syncRoles(['assistant']);
    }
}