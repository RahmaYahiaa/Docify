<?php

namespace Database\Seeders;

use App\Enums\Payment\TransactionTypeEnum;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $completedAppointments = Appointment::where('status', 'completed')
            ->whereDoesntHave('payment')
            ->with(['doctor.doctorProfile'])
            ->limit(50)
            ->get();

        foreach ($completedAppointments as $appointment) {
            $profile = DoctorProfile::where('user_id', $appointment->doctor_id)->first();
            if (! $profile) {
                continue;
            }

            $fee             = $appointment->type === 'video'
                ? ($profile->video_fee ?? 200)
                : ($profile->in_person_fee ?? 300);

            $platformFeeRate = 0.10; // 10%
            $platformFee     = round($fee * $platformFeeRate, 2);
            $doctorAmount    = round($fee - $platformFee, 2);
            $paidAt          = Carbon::now()->subDays(rand(1, 30));
            $paymentMethod   = ['card', 'cash'][rand(0, 1)];

            Payment::create([
                'appointment_id'       => $appointment->id,
                'patient_id'           => $appointment->patient_id,
                'doctor_id'            => $appointment->doctor_id,
                'amount'               => $fee,
                'consultation_fee'     => $fee,
                'platform_fee'         => $platformFee,          // ← stored as actual amount (decimal), not percentage
                'doctor_amount'        => $doctorAmount,
                'actual_payout_amount' => $doctorAmount,
                'platform_amount'      => $platformFee,
                'currency'             => 'EGP',
                'payment_method'       => $paymentMethod,
                'status'               => 'paid',
                'payout_status'        => 'paid',
                'paid_at'              => $paidAt,
                'payout_at'            => $paidAt->copy()->addDays(rand(1, 3)),
            ]);

            // IMPORTANT: Wallet uses 'doctor_id' column, NOT 'user_id'
            $wallet = Wallet::firstOrCreate(
                ['doctor_id' => $appointment->doctor_id],
                ['balance'   => 0]
            );

            $wallet->increment('balance', $doctorAmount);

            // IMPORTANT: WalletTransaction has no 'reference' column — use 'description'
            WalletTransaction::create([
                'wallet_id'   => $wallet->id,
                'amount'      => $doctorAmount,
                'type' => TransactionTypeEnum::EARNING,
                'status'      => 'completed',
                'description' => 'Appointment payout #' . $appointment->id,
            ]);
        }

        // Create empty wallets for all doctors who don't have one yet
        $doctorIds = Appointment::distinct()->pluck('doctor_id');
        foreach ($doctorIds as $doctorId) {
            Wallet::firstOrCreate(
                ['doctor_id' => $doctorId],
                ['balance'   => 0]
            );
        }
    }
}