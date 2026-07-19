<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('telescope:prune --hours=48')->daily();

Schedule::command('medications:notify')->everyMinute();

Schedule::command('appointments:reminders')->everyFiveMinutes();

// Safety net: close JaaS video rooms still running past appointment end time
Schedule::command('video:close-orphaned-sessions')->everyFifteenMinutes();
