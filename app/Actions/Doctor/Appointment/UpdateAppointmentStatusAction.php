<?php

namespace App\Actions\Doctor\Appointment;

use App\Actions\Payment\DoctorPayoutAction;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Payment\PayoutTypeEnum;
use App\Models\Appointment;

class UpdateAppointmentStatusAction
{
    public function __construct(
        private readonly DoctorPayoutAction $doctorPayoutAction
    ) {}

    public function execute(Appointment $appointment, string $status): Appointment
    {
        // تحديث الحالة
        $appointment->update([
            'status' => $status,
        ]);

        // تحديد نوع الـ payout حسب الحالة
        $payoutType = null;

     
        if ($status === AppointmentStatusEnum::COMPLETED->value) {
            $payoutType = PayoutTypeEnum::COMPLETED;
        }

        // الحالة 2: المريض لم يحضر
        elseif ($status === AppointmentStatusEnum::NO_SHOW->value) {
            $payoutType = PayoutTypeEnum::NO_SHOW;
        }

        // الحالة 3: إلغاء الموعد
        elseif ($status === AppointmentStatusEnum::CANCELLED->value) {

            // نتحقق هل الإلغاء متأخر (أقل من 24 ساعة)
            if ($this->isLateCancel($appointment)) {
                $payoutType = PayoutTypeEnum::LATE_CANCEL;
            }
        }

        // تنفيذ إضافة الرصيد للـ Wallet لو الحالة تستحق
        if ($payoutType) {
            $this->doctorPayoutAction->execute($appointment, $payoutType);
        }

        return $appointment;
    }

    private function isLateCancel(Appointment $appointment): bool
    {
        $hoursDifference = now()->diffInHours($appointment->start_at);
        return $hoursDifference < 24;
    }
}