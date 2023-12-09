<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class CheckNewNotificationsService
{

    // --- ---- -------- --- -------- --- -------------
    // The Main Function For Checking New Notifications
    // --- ---- -------- --- -------- --- -------------

    public function handle()
    {
        $count = $this->getNewNotificationsCount();
        return response()->json(['count' => $count], 200);
    }

    // ---- ------ -- --- ------- --- ------------- -----
    // This Method Is For Getting New Notifications Count
    // ---- ------ -- --- ------- --- ------------- -----

    private function getNewNotificationsCount()
    {
        return Notification::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->where('seen', 0)
            ->count();
    }

}