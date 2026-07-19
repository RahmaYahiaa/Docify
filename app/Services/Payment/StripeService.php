<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    public function createPaymentIntent(int $amountInCents, string $currency, array $metadata = []): PaymentIntent
    {
        try {
            return PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => $currency,
                'metadata' => $metadata,
                'automatic_payment_methods' => ['enabled' => true],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: createPaymentIntent failed', [
                'error' => $e->getMessage(),
                'code'  => $e->getStripeCode(),
            ]);
            throw $e;
        }
    }

    public function refund(string $paymentIntentId, int $amountInCents): Refund
    {
        try {
            return Refund::create([
                'payment_intent' => $paymentIntentId,
                'amount' => $amountInCents,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: refund failed', [
                'payment_intent_id' => $paymentIntentId,
                'amount' => $amountInCents,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function constructWebhookEvent(string $payload, string $signature): Event
    {
        return Webhook::constructEvent(
            $payload,
            $signature,
            config('stripe.webhook_secret')
        );
    }

    public function transferToConnectedAccount(string $accountId, float $amount, string $description = ''): \Stripe\Transfer
    {
        try {
            return \Stripe\Transfer::create([
                'amount'      => (int) ($amount * 100),
                'currency'    => config('stripe.currency', 'usd'),
                'destination' => $accountId, 
                'description' => $description,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Transfer failed', [
                'doctor_account' => $accountId,
                'amount'         => $amount,
                'error'          => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
