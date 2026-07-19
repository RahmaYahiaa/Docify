<?php

namespace App\Actions\Assistant;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Appointment\AppointmentTypeEnum;
use App\Models\Appointment;
use App\Models\Payment;
use Log;

class GetTodayFinancialDashboardAction
{
    public function execute($user): array
    {
        $doctorId = $user->doctor_id;
        $doctorFees = $user->doctor->doctorProfile->in_person_fee ?? 0;
        $today = now()->toDateString();

        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereHas('slot', function ($query) use ($today) {
                $query->whereDate('date', $today);
            })->get();

        $scheduled = $todayAppointments->count();
        $completed = $todayAppointments->where('status', 'completed')->count();
        $cancelled = $todayAppointments->where('status', 'cancelled')->count();
        $patientsHandled = $todayAppointments->whereIn('status', ['completed', 'cancelled'])->count();

        $clinicVisits = $todayAppointments->where('type', 'in_person')->count();
        $onlineConsultations = $todayAppointments->where('type', 'video')->count();
        $noShow = $todayAppointments->where('status', 'no_show')->count();

        $onlineStats = Payment::getTodayDoctorEarnings($doctorId);
        $cashAppointmentsCount = $todayAppointments->where('status', AppointmentStatusEnum::COMPLETED)
                                                ->where('type', AppointmentTypeEnum::IN_PERSON)
                                                ->count();

        $cashAmount = (float) ($cashAppointmentsCount * $doctorFees);
        $onlineAmount = (float) ($onlineStats->amount ?? 0);
        $onlineCount = (int) ($onlineStats->count ?? 0);


        $totalRevenue = $cashAmount + $onlineAmount;
        $totalPaymentsCount = $cashAppointmentsCount + $onlineCount;

        return [
            'clinic_day' => [
                'date' => now()->format('l, F j, Y'),
              
            ],
            'operational_stats' => [
                'patients_handled' => $patientsHandled,
                'scheduled'        => $scheduled,
                'completed'        => $completed,
                'cancelled'        => $cancelled,
            ],
            'appointment_types' => [
                'clinic_visits'         => $clinicVisits,
                'online_consultations'  => $onlineConsultations,
                'no_show'               => $noShow,
            ],
            'presence_status' => [
                'physically_attended' => $todayAppointments->where('status', 'completed')->where('type', 'in_person')->count(),
                'online_attended'     => $todayAppointments->where('status', 'completed')->where('type', 'video')->count(),
                'checked_in_not_completed' => $todayAppointments->whereNotNull('check_in_at')->where('status', 'pending')->count(),
            ],

            'payment_summary' => [

                'card' => [
                    'amount' => $onlineAmount,
                    'count'  => $onlineCount,
                ],
                'cash' => [
                    'amount' => $cashAmount,
                    'count'  => $cashAppointmentsCount,
                ],

                'total_revenue' => $totalRevenue,
                'total_count'   => $totalPaymentsCount,
                'card_amount'   => $onlineAmount,
                'cash_amount'   => $cashAmount,
            ],
        ];
    }
}