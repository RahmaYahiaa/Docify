<?php

namespace App\Enums\Availability;

enum AvailabilitySlotStatusEnum: string
{
    case AVAILABLE = 'available';
    case LOCKED    = 'locked';
    case BOOKED    = 'booked';
    case BLOCKED   = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::LOCKED    => 'Locked',
            self::BOOKED    => 'Booked',
            self::BLOCKED   => 'Blocked',
        };
    }

    public function isAvailable(): bool
    {
        return $this === self::AVAILABLE;
    }

    public function isLocked(): bool
    {
        return $this === self::LOCKED;
    }

    public function isBooked(): bool
    {
        return $this === self::BOOKED;
    }
}
