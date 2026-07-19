<?php

namespace App\Actions\Appointment\Video;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Appointment\AppointmentTypeEnum;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentException;
use App\Models\Appointment;
use App\Models\User\User;
use App\Services\Video\JitsiService;
use Illuminate\Support\Str;

class GenerateVideoSessionTokenAction
{
    public function __construct(
        private readonly JitsiService $jitsiService
    ) {}

    public function execute(Appointment $appointment, User $user): array
    {
        [$role, $moderator] = $this->resolveRole($appointment, $user);

        if ($appointment->type !== AppointmentTypeEnum::VIDEO) {
            throw new InvalidArgumentException(__('messages.video_session_not_applicable'));
        }

        if ($appointment->status !== AppointmentStatusEnum::CONFIRMED) {
            throw new InvalidArgumentException(__('messages.video_session_appointment_not_confirmed'));
        }

        $this->assertWithinJoinWindow($appointment);

        if (!$appointment->video_session_name) {
            $appointment->video_session_name = 'docify_' . $appointment->id . '_' . Str::random(8);
            $appointment->save();
        }

        $slot          = $appointment->slot;
        $startsAt      = $slot->date->setTimeFromTimeString($slot->getRawOriginal('start_time'));
        $sessionEndsAt = $startsAt->copy()->addMinutes($slot->duration_minutes);

        $tokenData = $this->jitsiService->generateToken(
            roomName: $appointment->video_session_name,
            role: $role,
            userId: $user->id,
            userName: $user->full_name,
            moderator: $moderator,
        );

        return array_merge($tokenData, [
            'session_duration_minutes' => $slot->duration_minutes,
            'session_ends_at'          => $sessionEndsAt->toIso8601String(),
        ]);
    }

    private function resolveRole(Appointment $appointment, User $user): array
    {
        if ($appointment->doctor_id === $user->id) {
            return [JitsiService::ROLE_MODERATOR, true];
        }

        if ($appointment->patient_id === $user->id) {
            return [JitsiService::ROLE_PARTICIPANT, false];
        }

        throw new ForbiddenException(__('messages.unauthorized_action'));
    }

    private function assertWithinJoinWindow(Appointment $appointment): void
    {
        $slot    = $appointment->slot;
        $startsAt = $slot->date->setTimeFromTimeString($slot->getRawOriginal('start_time'));
        $endsAt   = $startsAt->copy()->addMinutes($slot->duration_minutes);

        $windowOpens  = $startsAt->copy()->subMinutes((int) config('jitsi.join_before_minutes'));
        $windowCloses = $endsAt->copy()->addMinutes((int) config('jitsi.join_after_minutes'));

        if (now()->isBefore($windowOpens)) {
            throw new InvalidArgumentException(__('messages.video_session_too_early', [
                'minutes' => config('jitsi.join_before_minutes'),
            ]));
        }

        if (now()->isAfter($windowCloses)) {
            throw new InvalidArgumentException(__('messages.video_session_window_closed'));
        }
    }
}
