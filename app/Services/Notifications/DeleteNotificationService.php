<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class DeleteNotificationService
{

    // --- ---- ------ --- -------- ------------
    // The Main Method For Deleting Notification
    // --- ---- ------ --- -------- ------------

    public function handle($id)
    {
        $count = $this->deleteNotification($id);
        if ($count) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Deleting Notification
    // ---- ------ -- --- -------- ------------

    private function deleteNotification($id)
    {
        return Notification::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('sender_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

}