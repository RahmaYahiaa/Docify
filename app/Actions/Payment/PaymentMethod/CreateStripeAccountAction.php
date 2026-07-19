<?php

namespace App\Actions\Payment\PaymentMethod;

use App\Models\User\User;
use Stripe\StripeClient;
use Exception;

class CreateStripeAccountAction
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }


    public function execute(User $doctor): string
    {
        $profile = $doctor->doctorProfile;


        if ($profile && $profile->stripe_account_id) {
            return $profile->stripe_account_id;
        }

        try { 
            $account = $this->stripe->accounts->create([
                'type' => 'custom',
                'country' => 'US',
                'email' => $doctor->email,
            ]);

            if ($profile) {
                $profile->update([
                    'stripe_account_id' => $account->id,
                ]);
            }

            return $account->id;

        } catch (Exception $e) {
            throw new Exception("فشل إنشاء حساب سترايب الكاستم: " . $e->getMessage());
        }
    }
}