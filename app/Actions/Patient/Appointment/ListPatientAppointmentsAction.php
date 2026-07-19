<?php

namespace App\Actions\Patient\Appointment;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPatientAppointmentsAction
{
    public function execute(User $patient, string $filter = 'upcoming'): LengthAwarePaginator
    {
        $query = $patient->appointmentsAsPatient()
            ->with(['doctor.doctorProfile', 'slot', 'slot.clinic','review'])
            ->orderByDesc('created_at');

        match ($filter) {
            'upcoming' => $query = $query
                ->whereIn('status', [AppointmentStatusEnum::CONFIRMED->value])
                ->whereHas('slot', fn($q) => $q->whereRaw(
                    "ADDTIME(CONCAT(date, ' ', start_time), SEC_TO_TIME(duration_minutes * 60)) > ?",
                    [now()]
                )),

            'past' => $query = $query->where(function ($q) {
                $q->whereIn('status', [
                    AppointmentStatusEnum::COMPLETED->value,
                    AppointmentStatusEnum::CANCELLED->value,
                    AppointmentStatusEnum::NO_SHOW->value,
                ])->orWhere(function ($q2) { 
                    $q2->where('status', AppointmentStatusEnum::CONFIRMED->value)
                       ->whereHas('slot', fn($sq) => $sq->whereRaw(
                           "ADDTIME(CONCAT(date, ' ', start_time), SEC_TO_TIME(duration_minutes * 60)) <= ?",
                           [now()]
                       ));
                });
            }),

            default => null,
        };

        return $query->paginate(15);
    }
}
