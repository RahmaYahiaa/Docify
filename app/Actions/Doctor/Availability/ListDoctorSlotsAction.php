<?php

namespace App\Actions\Doctor\Availability;

use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ListDoctorSlotsAction
{
    public function execute(User $doctor, array $filters = []): LengthAwarePaginator
    {
        $query = DoctorAvailabilitySlot::query()
            ->where('doctor_id', $doctor->id)
            ->with('clinic')
            ->orderBy('date')
            ->orderBy('start_time');

        if (!empty($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (!empty($filters['from'])) {
            $query->where('date', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('date', '<=', $filters['to']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (empty($filters['date']) && empty($filters['from'])) {
            $query->where('date', '>=', today());
        }

        return $query->paginate(20);
    }
}