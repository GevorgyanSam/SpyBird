<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class SetSeenNotificationsService
{

    // ---- ------ -- -------- -- --- ---- -- -------------
    // This Method Is Designed To Set Seen On Notifications
    // ---- ------ -- -------- -- --- ---- -- -------------

    public function handle()
    {
        $count = $this->setSeenNotifications();
        if ($count) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- -------- -- --- ---- -- -------------
    // This Method Is Designed To Set Seen On Notifications
    // ---- ------ -- -------- -- --- ---- -- -------------

    private function setSeenNotifications()
    {
        return Notification::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->where('seen', 0)
            ->update([
                'seen' => 1,
                'updated_at' => now()
            ]);
    }

}