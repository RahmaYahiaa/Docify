<?php

namespace App\Actions\Payment;

use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Models\DoctorAvailabilitySlot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;

class HandleFailedPaymentAction
{
    public function execute(PaymentIntent $paymentIntent): void
    {
        $cacheKey = $paymentIntent->metadata->cache_key ?? null;

        if (!$cacheKey) {
            return;
        }

        $cached = Cache::get($cacheKey);

        if (!$cached) {
            return;
        }

        $slot = DoctorAvailabilitySlot::find($cached['slot_id']);

        if ($slot?->isLocked()) {
            $slot->update([
                'status' => AvailabilitySlotStatusEnum::AVAILABLE,
                'locked_until' => null,
            ]);
        }

        Cache::put(
            "payment_result_{$cacheKey}",
            [
                'status' => 'failed',
            ],
            now()->addMinutes(10)
        );

        Cache::forget($cacheKey);

        Log::info('Webhook: payment failed, slot released', [
            'slot_id' => $cached['slot_id'],
            'payment_intent_id' => $paymentIntent->id,
        ]);
    }
}
