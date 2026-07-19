<?php

namespace App\Http\Resources\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDashboardResource extends JsonResource
{
   public function toArray(Request $request): array
    {
        $doctorStats = User::role(UserRoleEnum::DOCTOR->value)
            ->selectRaw("
                count(*) as total,
                sum(case when status = 'active' then 1 else 0 end) as verified,
                sum(case when status = 'pending' then 1 else 0 end) as pending
            ")->first();

        $appointmentStats = Appointment::whereDate('created_at', today())
            ->selectRaw("
                count(*) as total,
                sum(case when status = 'completed' then 1 else 0 end) as completed,
                sum(case when status = 'upcoming' then 1 else 0 end) as upcoming
            ")->first();

        $revenueToday = Payment::whereDate('created_at', today())
            ->whereNull('refund_type')
            ->selectRaw("
                COALESCE(sum(amount), 0) as total,
                COALESCE(sum(case when payment_method = 'cash' then amount else 0 end), 0) as cash,
                COALESCE(sum(case when payment_method = 'card' then amount else 0 end), 0) as card
            ")->first();

        $refundsToday = Payment::whereDate('created_at', today())
            ->whereNotNull('refund_type')
            ->selectRaw("
                COALESCE(sum(refund_amount), 0) as total_amount,
                count(*) as total_transactions
            ")->first();

        return [
            'doctors' => [
                'total' => (int) ($doctorStats->total ?? 0),
                'verified' => (int) ($doctorStats->verified ?? 0),
                'pending' => (int) ($doctorStats->pending ?? 0),
            ],

            'patients' => [
                'total' => User::role(UserRoleEnum::PATIENT->value)->count(),
            ],

            'appointments' => [
                'total' => (int) ($appointmentStats->total ?? 0),
                'completed' => (int) ($appointmentStats->completed ?? 0),
                'upcoming' => (int) ($appointmentStats->upcoming ?? 0),
            ],

            'revenue' => [
                'total' => (float) ($revenueToday->total ?? 0),
                'cash' => (float) ($revenueToday->cash ?? 0),
                'card' => (float) ($revenueToday->card ?? 0),
            ],

            'refunds' => [
                'amount' => (float) ($refundsToday->total_amount ?? 0),
                'transactions_count' => (int) ($refundsToday->total_transactions ?? 0),
            ],
        ];
    }
}