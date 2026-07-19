<?php

namespace App\Actions\Payment;

use App\Models\User\User;
use Stripe\Stripe;
use Stripe\Account;

class CreateStripeAccountAction
{
    public function execute(User $doctor)
    {
        Stripe::setApiKey(config('stripe.secret_key'));

        $account = Account::create([
            'type' => 'express',
            'country' => 'US',
            'email' => $doctor->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);

        // ❗ IMPORTANT: رجّع الـ ID فقط
        return $account->id;
    }
}