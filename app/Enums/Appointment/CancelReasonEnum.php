<?php

namespace App\Enums\Appointment;

enum CancelReasonEnum: string
{
    case PERSONAL = 'personal';
    case FOUND_ANOTHER_DOCTOR = 'found_another_doctor';
    case NO_LONGER_NEEDED = 'no_longer_needed';
    case FINANCIAL = 'financial';
    case SCHEDULE_CONFLICT = 'schedule_conflict';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PERSONAL => 'Personal reasons',
            self::FOUND_ANOTHER_DOCTOR => 'Found another doctor',
            self::NO_LONGER_NEEDED => 'No longer needed',
            self::FINANCIAL => 'Financial reasons',
            self::SCHEDULE_CONFLICT => 'Schedule conflict',
            self::OTHER => 'Other',
        };
    }
}
