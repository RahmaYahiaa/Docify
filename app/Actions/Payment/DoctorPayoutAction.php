<?php

namespace App\Actions\Payment;

use App\Models\Wallet;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Enums\Payment\PayoutStatusEnum;
use App\Enums\Payment\PayoutTypeEnum;
use App\Enums\Payment\TransactionStatusEnum;
use App\Enums\Payment\TransactionTypeEnum;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\StripeService;
use Exception;

class DoctorPayoutAction
{
    public function __construct(
        private readonly StripeService $stripeService
    ) {}

    public function execute(Appointment $appointment, PayoutTypeEnum $payoutType = PayoutTypeEnum::COMPLETED): void
    {
        $payment = $appointment->payment;
        if (!$payment) {
            Log::error("Payout Failed: No payment for appointment #{$appointment->id}");
            throw new NotFoundException('Payment not found.');
        }

        if ($payment->payout_status !== PayoutStatusEnum::PENDING) {
            return;
        }

        $doctorPct = config("stripe.{$payoutType->doctorPercentageKey()}", 80) / 100;
        $doctorAmount = round($payment->amount * $doctorPct, 2);

        //  تعديل: هنجيب الـ stripe_account_id وحالة ربط البنك كمان مع بعض
        $doctorInfo = DB::table('doctor_profiles')
            ->where('user_id', (int) $appointment->doctor_id)
            ->first(['stripe_account_id', 'bank_account_added']);

        $stripeAccountId = $doctorInfo?->stripe_account_id;
        $isBankLinked = $doctorInfo?->bank_account_added ?? false;

        Log::info("Processing Payout for Appointment #{$appointment->id}", [
            'doctor_id' => $appointment->doctor_id,
            'stripe_id' => $stripeAccountId ?? 'NOT_FOUND',
            'bank_linked' => $isBankLinked ? 'YES' : 'NO',
            'amount'    => $doctorAmount
        ]);

        DB::beginTransaction();
        try {
            // 1. تحديث المحفظة المحلية (Local Wallet) الخاصة بالدكتور داخل موقعنا
            $wallet = Wallet::firstOrCreate(
                ['doctor_id' => $appointment->doctor_id],
                ['balance' => 0]
            );
            $wallet->increment('balance', $doctorAmount);

            $wallet->transactions()->create([
                'amount'      => $doctorAmount,
                'type'        => TransactionTypeEnum::EARNING,
                'status'      => TransactionStatusEnum::COMPLETED,
                'description' => __('messages.appointment_earning', ['id' => $appointment->id]),
            ]);

            $payoutStatus = PayoutStatusEnum::PROCESSING;

            if ($stripeAccountId && $isBankLinked) {
                try {
                    $this->stripeService->transferToConnectedAccount(
                        $stripeAccountId,
                        $doctorAmount,
                        "Payout for appointment #{$appointment->id}"
                    );

                    $payoutStatus = PayoutStatusEnum::PAID;
                    Log::info("Stripe Transfer Success: {$stripeAccountId}");
                } catch (Exception $e) {
                    $payoutStatus = PayoutStatusEnum::PROCESSING;
                    Log::error("Stripe Transfer Error for Appointment #{$appointment->id}: " . $e->getMessage());
                }
            } else {
                Log::warning("Stripe Transfer Skipped for Appointment #{$appointment->id}: Stripe Account Missing or Bank Not Linked.");
            }
 
            $payment->update([
                'payout_status'        => $payoutStatus,
                'actual_payout_amount' => $doctorAmount,
                'payout_initiated_at'  => now(),
                'payout_at'            => $payoutStatus === PayoutStatusEnum::PAID ? now() : null,
            ]);

            DB::commit();
            Log::info("Payout Completed Successfully for Appointment #{$appointment->id}");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Critical Payout Failure: " . $e->getMessage());
            throw $e;
        }
    }
}