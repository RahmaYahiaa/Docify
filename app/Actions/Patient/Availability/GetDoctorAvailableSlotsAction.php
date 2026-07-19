<?php

namespace App\Actions\Patient\Availability;

use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Exceptions\InvalidArgumentException;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GetDoctorAvailableSlotsAction
{
    public function execute(User $doctor, string $type, Carbon $date, int $perPage = 20): LengthAwarePaginator
    {
        if (!$doctor->isDoctor()) {
            throw new InvalidArgumentException(__('messages.doctor_not_found'));
        }

        return DoctorAvailabilitySlot::query()
            ->where('doctor_id', $doctor->id)
            ->where('date', $date->format('Y-m-d'))
            ->where('type', $type)
            ->whereIn('status', [
                AvailabilitySlotStatusEnum::AVAILABLE->value,
                AvailabilitySlotStatusEnum::LOCKED->value,
                AvailabilitySlotStatusEnum::BOOKED->value,
            ])
            ->orderBy('start_time')
            ->paginate($perPage);
    }
}
