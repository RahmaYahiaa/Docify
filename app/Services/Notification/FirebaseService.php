<?php

namespace App\Services\Notification;

use App\Models\FcmToken;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    public function __construct(protected Messaging $messaging) {}

    public function sendToTokens(array $tokens, string $title, string $body, array $data = [])
    {
        if (empty($tokens)) return null;

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData(array_map('strval', $data))
            ->withAndroidConfig([
                'priority' => 'high'
            ])
            ->withApnsConfig([
                'headers' => ['apns-priority' => '10']
            ]);

        $report = $this->messaging->sendMulticast($message, $tokens);

        if ($report->hasFailures()) {
            $badTokens = $report->unknownTokens();
            FcmToken::whereIn('token', $badTokens)->delete();
        }
        
        return $report;
    }
}
