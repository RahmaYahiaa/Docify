<?php

use App\Http\Controllers\API\V1\Payment\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/payments/webhook', [PaymentWebhookController::class, 'handle'])
    ->name('payments.webhook');
