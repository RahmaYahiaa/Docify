<?php

namespace App\Actions\Payment;

use App\Enums\Payment\PaymentStatusEnum;
use App\Enums\Payment\RefundTypeEnum;
use App\Events\Notification\PaymentRefunded;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\Models\Appointment;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\Log;

class RefundPaymentAction
{
    public function __construct(
        private readonly StripeService $stripeService
    ) {}
    public function execute(Appointment $appointment): void
    {
        $payment = $appointment->payment;

        if (!$payment) {
            throw new NotFoundException(__('messages.payment_not_found'));
        }

        if (!$payment->isRefundable()) {
            throw new InvalidArgumentException(__('messages.payment_not_refundable'));
        }

        $refundAmount = $payment->consultation_fee;

        $refund = $this->stripeService->refund(
            paymentIntentId: $payment->stripe_payment_intent_id,
            amountInCents: (int) round($refundAmount * 100),
        );

        $payment->update([
            'status' => PaymentStatusEnum::REFUNDED,
            'refund_type' => RefundTypeEnum::FULL,
            'refund_amount' => $refundAmount,
            'refunded_at' => now(),
        ]);
        
        event(new PaymentRefunded(
            appointment: $appointment,
            refundAmount: $refundAmount,
        ));

        // activity()
        //     ->performedOn($appointment)
        //     ->causedBy("System")
        //     ->withProperties([
        //         'amount' => "Amount: {$appointment->payment?->amount}",
        //         'status' => 'refunded',
        //         'message' => "Refund issued for appointment with Dr. {$appointment->doctor?->full_name}",
        //     ])
        //     ->log('Refund Processed');

        Log::info('Refund processed', [
            'appointment_id' => $appointment->id,
            'payment_id' => $payment->id,
            'refund_id' => $refund->id,
            'refund_amount' => $refundAmount,
        ]);
    }
}
