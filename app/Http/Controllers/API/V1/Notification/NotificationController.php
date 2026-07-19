<?php

namespace App\Http\Controllers\API\V1\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Notification\NotificationCollection;
use App\Models\Notification;


class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);

        return $this->ok(data: new NotificationCollection($notifications));
    }

    public function destory(Notification $notification)
    {
        $notification->delete();

        return $this->ok(__('messages.notification_deleted_successfully'));
    }

    public function deleteAllNotifications()
    {
        auth()->user()->notifications()->delete();

        return $this->ok(__('messages.notification_deleted_successfully'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();

        return $this->ok(__('messages.notification_marked_as_read'));
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return $this->ok(__('messages.all_notifications_marked_as_read'));
    }
}
