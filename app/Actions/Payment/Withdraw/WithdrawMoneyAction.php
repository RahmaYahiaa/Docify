<?php

namespace App\Actions\Payment\Withdraw;

use App\Enums\Payment\TransactionStatusEnum;
use App\Enums\Payment\TransactionTypeEnum;
use App\Models\User\User;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Payout;
use Stripe\Stripe;

class WithdrawMoneyAction
{

    public function execute(User $doctor, $amount)
    {

        Stripe::setApiKey(config('stripe.secret_key'));

        return DB::transaction(function () use ($doctor, $amount) {


            $stripeAccountId = $doctor->doctorProfile?->stripe_account_id;

            if (!$stripeAccountId) {
                throw new Exception('Sorry, no Stripe Connect account is linked to this doctor.');
            }


            $wallet = $doctor->wallet()->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new Exception('Your current wallet balance is insufficient to complete the withdrawal.');
            }

            try {

                $stripePayout = Payout::create(
                    [
                        'amount'   => (int)($amount * 100),
                        'currency' => config('stripe.currency', 'usd'),
                        'description' => "Withdrawal for Doctor: {$doctor->name}",
                    ],
                    [
                        'stripe_account' => $stripeAccountId,
                    ]
                );

                $wallet->decrement('balance', $amount);


                return WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'doctor_id'    => $doctor->id,
                    'amount'       => $amount,
                    'type'         => TransactionTypeEnum::WITHDRAWAL,
                    'status'       => TransactionStatusEnum::COMPLETED,
                    'reference_id' => $stripePayout->id,
                    'description'  => "The amount has been successfully withdrawn to your bank account.",
                ]);

                Log::info("Withdrawal Success: Doctor #{$doctor->id} withdrew {$amount}");

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error("Stripe Error: " . $e->getMessage());
                throw new Exception('Stripe withdrawal failed: ' . $e->getMessage());
            } catch (\Exception $e) {
                Log::error("General Error: " . $e->getMessage());

                throw new Exception($e->getMessage());
            }
        });
    }
}