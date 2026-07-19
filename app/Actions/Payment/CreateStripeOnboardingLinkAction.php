<?php

namespace App\Actions\Payment;

use App\Models\User\User;

use Stripe\Stripe;
use Stripe\StripeClient;

class CreateStripeOnboardingLinkAction
{private $stripeClient;


    public function __construct()
    {
    $this->stripeClient = new StripeClient(config('stripe.secret_key'));
    }
public function execute(User $doctor)
{
    $stripeAccountId = $doctor->doctorProfile->stripe_account_id;

    if (!$stripeAccountId) {
        $stripeAccount = $this->stripeClient->accounts->create([
            'type' => 'express',
            'email' => $doctor->email,
            // دي أهم حتة يا رحمة عشان الحساب يتفعل صح
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers'     => ['requested' => true],
            ],
        ]);

        $stripeAccountId = $stripeAccount->id;

        $doctor->doctorProfile()->update([
            'stripe_account_id' => $stripeAccountId,
        ]);
    }

    // هنا بنعمل اللينك اللي هيخلي الدكتور يملى بيانات البنك بناءً على الـ capabilities اللي فوق
    $link = $this->stripeClient->accountLinks->create([
        'account' => $stripeAccountId,
        'refresh_url' => route('stripe.refresh'),
        'return_url' => route('stripe.return'),
        'type' => 'account_onboarding',
    ]);

    return $link->url;
}
}