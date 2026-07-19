<?php

namespace App\Enums\Patient;

enum MedicationFrequencyEnum: string
{
    case ONCE_DAILY = 'once daily';
    case TWICE_DAILY = 'twice daily';
    case THREE_TIMES_DAILY = 'three times daily';
    case EVERY_6_HOURS = 'every 6 hours';
    case EVERY_8_HOURS = 'every 8 hours';
    case EVERY_12_HOURS = 'every 12 hours';

    public function frequency(): int
    {
        return match ($this) {
            self::ONCE_DAILY        => 1,
            self::TWICE_DAILY       => 2,
            self::THREE_TIMES_DAILY => 3,
            self::EVERY_6_HOURS     => 4,
            self::EVERY_8_HOURS     => 3,
            self::EVERY_12_HOURS    => 2,
        };
    }

    public static function resolve(string|int|null $value): int
    {
        if (is_numeric($value)) {
            return max((int) $value, 1);
        }

        return self::tryFrom((string) $value)?->frequency() ?? 1;
    }
}
