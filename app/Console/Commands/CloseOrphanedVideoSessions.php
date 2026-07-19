<?php

namespace App\Console\Commands;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Appointment\AppointmentTypeEnum;
use App\Models\Appointment;
use App\Services\Video\JaaSServerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseOrphanedVideoSessions extends Command
{
    protected $signature   = 'video:close-orphaned-sessions';
    protected $description = 'Force-close JaaS video rooms still running past their scheduled end time.';

    public function handle(JaaSServerService $jaas): int
    {
        $threshold = (int) config('jitsi.orphan_threshold_minutes', 60);
        $cutoff    = now()->subMinutes($threshold);
        $closed    = 0;
        $errors    = 0;

        $candidates = Appointment::query()
            ->where('type', AppointmentTypeEnum::VIDEO)
            ->where('status', AppointmentStatusEnum::CONFIRMED)
            ->whereNotNull('video_session_name')
            ->with('slot')
            ->get()
            ->filter(function (Appointment $appt) use ($cutoff) {
                $slot = $appt->slot;
                if (!$slot) return false;

                $endsAt = Carbon::parse($slot->date)
                    ->setTimeFromTimeString($slot->getRawOriginal('start_time'))
                    ->addMinutes($slot->duration_minutes);

                return $endsAt->isBefore($cutoff);
            });

        if ($candidates->isEmpty()) {
            $this->info('No orphaned sessions found.');
            return self::SUCCESS;
        }

        $activeRooms = collect($jaas->listActiveRooms())->pluck('name')->flip();

        foreach ($candidates as $appointment) {
            $roomName = $appointment->video_session_name;

            if (!$activeRooms->has($roomName)) {
                continue; // already ended
            }

            $success = $jaas->destroyRoom($roomName);
            $success ? $closed++ : $errors++;

            $this->line(($success ? 'Closed' : 'Failed') . " [{$roomName}] appointment #{$appointment->id}");
        }

        $this->info("Done. Closed: {$closed}. Failures: {$errors}.");
        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
