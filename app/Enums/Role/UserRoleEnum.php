<?php

namespace App\Enums\Role;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';
    case ASSISTANT = 'assistant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::DOCTOR => 'Doctor',
            self::PATIENT => 'Patient',
            self::ASSISTANT => 'Doctor Assistant',
        };
    }

    public function isDoctor(): bool
    {
        return $this === self::DOCTOR;
    }

    public function isPatient(): bool
    {
        return $this === self::PATIENT;
    }
}
