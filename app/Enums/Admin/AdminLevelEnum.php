<?php

namespace App\Enums\Admin;

enum AdminLevelEnum: string
{
    case ADMIN = 'admin';
    case SUPERADMIN = 'super admin';


    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::SUPERADMIN => 'Super Admin',
        };
    }
}
