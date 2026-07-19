<?php

namespace App\Actions\Payment\PaymentMethod;

use App\Models\User\User;
use Stripe\StripeClient;
use Exception;

class AddDoctorBankAccountAction
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    /**
     * توليد توكن البنك وربطه بحساب الطبيب الكاستم
     */
    public function execute(User $doctor, string $routingNumber, string $accountNumber): bool
    {
        $profile = $doctor->doctorProfile;

        if (!$profile || !$profile->stripe_account_id) {
            throw new Exception("الطبيب لا يملك حساب سترايب بعد.");
        }

        try {
            // 1. توليد الـ Bank Account Token داخلياً
            $tokenResponse = $this->stripe->tokens->create([
                'bank_account' => [
                    'country' => 'US',
                    'currency' => 'usd',
                    'routing_number' => $routingNumber,
                    'account_number' => $accountNumber,
                    'account_holder_type' => 'individual',
                ],
            ]);

            $stripeToken = $tokenResponse->id; // الـ btok_xxxx

            // 2. ربط التوكن بالحساب الكاستم الخاص بالدكتور مباشرة
            $this->stripe->accounts->createExternalAccount(
                $profile->stripe_account_id,
                [
                    'external_account' => $stripeToken,
                    'default_for_currency' => true,
                ]
            );

            // 3. تحديث حالة إضافة البنك في قاعدة البيانات
            $profile->update([
                'bank_account_added' => true,
            ]);

            return true;

        } catch (Exception $e) {
            throw new Exception("فشل ربط الحساب البنكي: " . $e->getMessage());
        }
    }
}