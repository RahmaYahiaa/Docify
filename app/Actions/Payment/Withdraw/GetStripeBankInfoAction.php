<?php

namespace App\Actions\Payment\Withdraw;



use Stripe\Stripe;
use Stripe\Account;

class GetStripeBankInfoAction
{
    public function execute($user): array
    {
        $stripeAccountId = $user->doctorProfile?->stripe_account_id;

        $bankData = [
            'bank_name' => 'Not Linked',
            'last4' => '****'
        ];

        if ($stripeAccountId) {
            Stripe::setApiKey(config('stripe.secret_key'));

            try {
                $account = Account::retrieve($stripeAccountId);
                $externalAccount = $account->external_accounts->data[0] ?? null;

                if ($externalAccount) {
                    $bankData['bank_name'] = $externalAccount->bank_name;
                    $bankData['last4'] = $externalAccount->last4;
                }
            } catch (\Exception $e) {

            }
        }

        return $bankData;
    }
}