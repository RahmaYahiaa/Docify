<?php

namespace App\Models\User;

trait UserAccessor
{
    public function getUserLocale(): string
    {
        return $this->locale == null ? config('app.locale') : $this->locale;
    }
}
