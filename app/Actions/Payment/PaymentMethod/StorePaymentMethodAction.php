<?php

namespace App\Actions\Payment\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\User\User;

class StorePaymentMethodAction
{
    public function execute(User $user, array $data): PaymentMethod
    {
        return $user->paymentMethods()->create([
            'type'                => $data['type'],
            'bank_name'           => $data['bank_name'] ?? null,
            'account_holder_name' => $data['account_holder_name'],
            'account_number'      => $data['account_number'],
            'iban'                => $data['iban'] ?? null,
        ]);
    }
}