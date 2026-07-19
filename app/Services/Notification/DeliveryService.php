<?php

namespace App\Services\Notification;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Mail\GenericMail;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DeliveryService
{
    public function __construct(protected FirebaseService $firebase) {}

    /**
     * @param User $user
     * @param NotificationType $type 
     * @param string $title
     * @param string $body
     * @param array $data
     */
    public function notifyUser(
        User $user,
        NotificationType $type,
        string $title,
        string $body,
        array $data = [],
        array $channels = [DeliveryChannel::PUSH, DeliveryChannel::IN_APP]
    ) {
        if (in_array(DeliveryChannel::PUSH, $channels)) {

            $tokens = $user->fcmTokens->pluck('token')->toArray();

            if (!empty($tokens)) {
                $fcmData = array_merge($data, [
                    'category' => $type->value
                ]);

                $this->firebase->sendToTokens(
                    $tokens,
                    $title,
                    $body,
                    $fcmData
                );
            }
        }

        if (in_array(DeliveryChannel::IN_APP, $channels)) {

            DB::table('notifications')->insert([
                'id' => Str::uuid()->toString(),
                'type' => 'in_app',
                'category' => $type->value,
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => $title,
                    'body' => $body,
                    'category' => $type->value,
                    'extra_data' => $data
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (in_array(DeliveryChannel::EMAIL, $channels)) {

            Mail::to($user->email)->send(
                new GenericMail($title, $body, $data)
            );
        }
    }
}
