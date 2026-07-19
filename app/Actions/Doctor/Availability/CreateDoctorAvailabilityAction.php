<?php

namespace App\Actions\Doctor\Availability;

use App\Exceptions\InvalidArgumentException;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateDoctorAvailabilityAction
{
    public function execute(User $doctor, array $data): void
    {
        $this->validateInput($data);
        $this->validateBusinessRules($doctor, $data);

        $start = Carbon::parse("{$data['date']} {$data['start_time']}");
        $end   = Carbon::parse("{$data['date']} {$data['end_time']}");

        if ($start->gte($end)) {
            throw new InvalidArgumentException(__('messages.start_time_must_be_before_end_time'));
        }

        $slots = $this->generateSlots($doctor, $data, $start, $end);

        DB::transaction(static fn() => DoctorAvailabilitySlot::insert($slots));
    }

    private function validateInput(array $data): void
    {
        if (! in_array($data['type'], ['video', 'in_person'])) {
            throw new InvalidArgumentException(__('messages.invalid_consultation_type'));
        }

        if ($data['type'] === 'in_person' && empty($data['clinic_id'])) {
            throw new InvalidArgumentException(__('messages.clinic_id_required_for_in_person_sessions'));
        }
    }

    private function validateBusinessRules(User $doctor, array $data): void
    {
        $profile = $doctor->doctorProfile;

        if ($data['type'] === 'video' && is_null($profile?->video_fee)) {
            throw new InvalidArgumentException(__('messages.video_fee_not_set'));
        }

        if ($data['type'] === 'in_person' && is_null($profile?->in_person_fee)) {
            throw new InvalidArgumentException(__('messages.in_person_fee_not_set'));
        }

        $start = Carbon::parse("{$data['date']} {$data['start_time']}");
        $end   = Carbon::parse("{$data['date']} {$data['end_time']}");

        $overlapping = DoctorAvailabilitySlot::where('doctor_id', $doctor->id)
            ->where('date', $data['date'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->whereRaw(
                        "ADDTIME(start_time, SEC_TO_TIME(duration_minutes * 60)) > ?",
                        [$start->format('H:i:s')]
                    );
            })
            ->exists();

        if ($overlapping) {
            throw new InvalidArgumentException(__('messages.overlapping_slots'));
        }

        if ($data['type'] === 'in_person') {
            if (!$profile || !$profile->clinic) {
                throw new InvalidArgumentException(__('messages.no_clinic_assigned_to_profile'));
            }

            if ($profile->clinic_id !== (int) $data['clinic_id']) {
                throw new InvalidArgumentException(__('messages.selected_clinic_not_assigned_to_profile'));
            }
        }
    }
    private function generateSlots(User $doctor, array $data, Carbon $start, Carbon $end): array
    {
        $slots = [];
        $current = $start->copy();

        while (true) {
            $next = $current->copy()->addMinutes($data['duration_minutes']);

            if ($next->gt($end)) {
                break;
            }

            $slots[] = [
                'doctor_id'        => $doctor->id,
                'date'             => $data['date'],
                'start_time'       => $current->format('H:i:s'),
                'duration_minutes' => $data['duration_minutes'],
                'type'             => $data['type'],
                'clinic_id'        => $data['clinic_id'] ?? null,
                'status'           => 'available',
                'created_at'       => now(),
                'updated_at'       => now(),
            ];

            $current = $next;
        }

        if (empty($slots)) {
            throw new InvalidArgumentException(__('messages.no_slots_generated'));
        }

        return $slots;
    }
}
