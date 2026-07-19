<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Actions\Payment\HandleFailedPaymentAction;
use App\Actions\Payment\HandleSuccessfulPaymentAction;
use App\Http\Controllers\Controller;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private readonly StripeService $stripeService,
        private readonly HandleSuccessfulPaymentAction $handleSuccess,
        private readonly HandleFailedPaymentAction $handleFailure,
    ) {}

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $this->stripeService->constructWebhookEvent($payload, $signature);
        } catch (SignatureVerificationException $e) {
            Log::warning('Webhook: invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        try {
            match ($event->type) {
                'payment_intent.succeeded' => $this->handleSuccess->execute($event->data->object),
                'payment_intent.payment_failed' => $this->handleFailure->execute($event->data->object),
                default => Log::info('Webhook: unhandled event', ['type' => $event->type]),
            };
        } catch (\Throwable $e) {
            Log::error('Webhook: handler failed', [
                'event_type' => $event->type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return response('OK', 200);
    }
}