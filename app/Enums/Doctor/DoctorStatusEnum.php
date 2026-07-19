<?php

namespace App\Enums\Doctor;

enum DoctorStatusEnum: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case BLOCKED = 'blocked';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::PENDING => 'Pending Approval',
            self::BLOCKED => 'Blocked',
            self::REJECTED => 'Rejected',
        };
    }
}
