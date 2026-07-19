<?php

namespace App\Actions\Doctor\Availability;

use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Exceptions\InvalidArgumentException;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BlockDoctorAvailabilityAction
{

    public function execute(User $doctor, array $data): void
    {
        $date = $data['date'];
        $start = Carbon::parse("{$date} {$data['start_time']}");
        $end = Carbon::parse("{$date} {$data['end_time']}");
        $type = $data['type'] ?? null;

        $bookedQuery = DoctorAvailabilitySlot::where('doctor_id', $doctor->id)
            ->where('date', $date)
            ->where('status', AvailabilitySlotStatusEnum::BOOKED)
            ->where('start_time', '>=', $start->format('H:i:s'))
            ->where('start_time', '<', $end->format('H:i:s'));

        if ($type) {
            $bookedQuery->where('type', $type);
        }

        if ($bookedQuery->exists()) {
            throw new InvalidArgumentException(
                __('messages.cannot_block_slots_with_appointments')
            );
        }

        DB::transaction(function () use ($doctor, $date, $start, $end, $type) {
            $query = DoctorAvailabilitySlot::where('doctor_id', $doctor->id)
                ->where('date', $date)
                ->whereIn('status', [
                    AvailabilitySlotStatusEnum::AVAILABLE,
                ])
                ->where('start_time', '>=', $start->format('H:i:s'))
                ->where('start_time', '<', $end->format('H:i:s'));

            if ($type) {
                $query->where('type', $type);
            }

            $query->update([
                'status' => AvailabilitySlotStatusEnum::BLOCKED,
            ]);
        });
    }
}
