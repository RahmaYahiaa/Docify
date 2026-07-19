<?php

namespace App\Enums\Appointment;

enum AppointmentStatusEnum: string
{
    case CONFIRMED  = 'confirmed';
    case COMPLETED  = 'completed';
    case CANCELLED  = 'cancelled'; 
    case NO_SHOW    = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::CONFIRMED => 'Confirmed',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::NO_SHOW   => 'No Show',
        };
    }
    public static function values(): array
{
    return array_column(self::cases(), 'value');
}

    public function isFinal(): bool
    {
        return in_array($this, [
            self::COMPLETED,
            self::CANCELLED,
            self::NO_SHOW,
        ]);
    }

    public function canCancel(): bool
    {
        return !$this->isFinal();
    }

    public function canReschedule(): bool
    {
        return $this === self::CONFIRMED;
    }
}
