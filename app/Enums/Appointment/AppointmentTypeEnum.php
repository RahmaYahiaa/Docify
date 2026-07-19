<?php

namespace App\Enums\Appointment;

enum AppointmentTypeEnum: string
{
    case VIDEO = 'video';
    case IN_PERSON = 'in_person';

    public function label(): string
    {
        return match ($this) {
            self::VIDEO => 'Video Consultation',
            self::IN_PERSON => 'In-Person Visit',
        };
    }

    public function requiresPayment(): bool
    {
        return $this === self::VIDEO;
    }

    public function icon(): string
    {
        return $this === self::VIDEO ? 'video-camera' : 'location';
    }
}
