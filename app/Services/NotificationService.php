<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Retrieve all notifications for a given user.
     *
     * @param int $userId
     * @return array
     */
    public function listNotifications($userId)
    {
        // Fetch notifications for the user
        $notifications = Notification::where('user_id', $userId)->get();

        if ($notifications->isEmpty()) {
            return ['success' => false, 'message' => 'No notifications found'];
        }

        return ['success' => true, 'data' => $notifications];
    }

    /**
     * Mark a notification as read.
     *
     * @param int $notificationId
     * @return array
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return ['success' => false, 'message' => 'Notification not found'];
        }

        $notification->update(['status' => 'read']);

        return ['success' => true, 'message' => 'Notification marked as read'];
    }
}
