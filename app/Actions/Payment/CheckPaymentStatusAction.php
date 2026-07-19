<?php

namespace App\Actions\Payment;

use Illuminate\Support\Facades\Cache;

class CheckPaymentStatusAction
{
    public function execute(string $cacheKey): array
    {
        $result = Cache::get("payment_result_{$cacheKey}");

        if ($result) {
            return match ($result['status']) {
                'confirmed' => [
                    'status' => 'confirmed',
                    'appointment_id' => $result['appointment_id'],
                    'message' => __('messages.payment_confirmed_successfully'),
                ],
                'failed' => [
                    'status' => 'failed',
                    'appointment_id' => null,
                    'message' => __('messages.payment_failed'),
                ],
                default => [
                    'status' => 'expired',
                    'appointment_id' => null,
                    'message' => __('messages.payment_expired'),
                ],
            };
        }

        if (Cache::get($cacheKey)) {
            return [
                'status' => 'pending',
                'appointment_id' => null,
                'message' => __('messages.payment_pending'),
            ];
        }

        return [
            'status' => 'expired',
            'appointment_id' => null,
            'message' => __('messages.payment_expired'),
        ];
    }
}
