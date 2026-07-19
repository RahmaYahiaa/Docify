<?php

namespace App\Http\Controllers\API\V1\Payment;


use App\Http\Controllers\Controller;


use Stripe\Stripe;
use Stripe\Account;
use Illuminate\Http\Request;
class PaymentMethodController extends Controller
{
public function bankInfo(Request $request)
{
    $doctor = $request->user();
    $stripeAccountId = $doctor->doctorProfile?->stripe_account_id;
    
    Stripe::setApiKey(config('stripe.secret_key'));
    $account = Account::retrieve($stripeAccountId);
    $externalAccounts = $account->external_accounts->data ?? [];
    $bankAccount = $externalAccounts[0] ?? null;
    return response()->json([
        'bank_name' => $bankAccount->bank_name ?? null,
        'last4' => $bankAccount->last4 ?? null,
    ]);
}
}
