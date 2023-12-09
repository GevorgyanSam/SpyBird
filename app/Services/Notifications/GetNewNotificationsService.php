<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class GetNewNotificationsService
{

    // --- ---- -------- --- ------- --- -------------
    // The Main Function For Getting New Notifications
    // --- ---- -------- --- ------- --- -------------

    public function handle()
    {
        $notifications = $this->getNewNotifications();
        if ($this->exists($notifications)) {
            return response()->json(['data' => $notifications], 200);
        }
        return response()->json(['empty' => true], 200);
    }

    // ---- ------ -- --- ------- --- -------------
    // This Method Is For Getting New Notifications
    // ---- ------ -- --- ------- --- -------------

    private function getNewNotifications()
    {
        return Notification::with('sender')
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->where('seen', 0)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    // ---- ------ -- -------- -- ----- ------------- -- ---
    // This Method Is Checking Is There Notifications Or Not
    // ---- ------ -- -------- -- ----- ------------- -- ---

    private function exists($notifications)
    {
        return count($notifications) ? true : false;
    }

}