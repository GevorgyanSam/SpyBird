<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class ClearNotificationsService
{

    // --- ---- -------- --- -------- -------------
    // The Main Function For Clearing Notifications
    // --- ---- -------- --- -------- -------------

    public function handle()
    {
        $count = $this->clearNotifications();
        if ($count) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- --- -------- -------------
    // This Method Is For Clearing Notifications
    // ---- ------ -- --- -------- -------------

    private function clearNotifications()
    {
        return Notification::where('user_id', auth()->user()->id)
            ->where('sender_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

}