<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Http\Controllers\Controller;
use App\Actions\Payment\CreateStripeAccountAction;
use App\Actions\Payment\PaymentMethod\AddDoctorBankAccountAction;
use Illuminate\Http\Request;
use Exception;

class StripeController extends Controller
{
    public function addBankAccount(
        Request $request,
        AddDoctorBankAccountAction $bankAction,
        CreateStripeAccountAction $stripeAction
    ) {
        $request->validate([
            'routing_number' => 'required|string',
            'account_number' => 'required|string',
        ]);

        try {
            $doctor = auth()->user();

            $stripeAccountId = $doctor->doctorProfile?->stripe_account_id;

            if (!$stripeAccountId) {
                $stripeAccountId = $stripeAction->execute($doctor);

                $doctor->doctorProfile()->firstOrCreate([
                    'stripe_account_id' => $stripeAccountId
                ]);

                $doctor->load('doctorProfile');
            }

            $bankAction->execute(
                $doctor,
                $request->routing_number,
                $request->account_number
            );

            return $this->ok(__('messages.bank_added_successfully'));

        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}