<?php

namespace App\Actions\Assistant;

use App\Models\Appointment;
use App\Enums\AppointmentTypeEnum;

class GetTodayAssistantFinancialSummaryAction
{
    public function execute($user): array
    {

        $appointments = Appointment::todayCash($user->doctor_id)
            ->with(['patient', 'slot'])
            ->get();

        $doctorFees = $user->doctor->doctorProfile->in_person_fee ?? 0;

        return [
            'pending_list'   => $appointments->filter(fn($apt) => !$apt->is_paid_cash),
            'collected_list' => $appointments->filter(fn($apt) => $apt->is_paid_cash),
            'doctor_fees'    => $doctorFees,
        ];
    }
}