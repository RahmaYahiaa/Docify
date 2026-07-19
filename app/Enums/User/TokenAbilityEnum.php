<?php

namespace App\Enums\User;

enum TokenAbilityEnum: string
{
    case ISSUE_ACCESS_TOKEN = 'issue-access-token';
    case ACCESS_API = 'access-api';
}
