<?php

namespace App\Enums\User;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case BLOCKED = 'blocked';
    case SUSPENDED = 'suspended';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
