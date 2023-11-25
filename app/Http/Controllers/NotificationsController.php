<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationsController extends Controller
{

    // ---- ------ -- --- ------- -------------
    // This Method Is For Getting Notifications
    // ---- ------ -- --- ------- -------------

    public function getNotifications()
    {
        $notifications = Notification::with('sender')
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
        if (count($notifications)) {
            return response()->json(['data' => $notifications], 200);
        }
        return response()->json(['empty' => true], 200);
    }

    // ---- ------ -- --- ------- --- -------------
    // This Method Is For Getting New Notifications
    // ---- ------ -- --- ------- --- -------------

    public function getNewNotifications()
    {
        $notifications = Notification::with('sender')
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->where('seen', 0)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
        if (count($notifications)) {
            return response()->json(['data' => $notifications], 200);
        }
        return response()->json(['empty' => true], 200);
    }

    // ---- ------ -- --- -------- --- -------------
    // This Method Is For Clearing All Notifications
    // ---- ------ -- --- -------- --- -------------

    public function clearNotifications()
    {
        $count = Notification::where('user_id', auth()->user()->id)
            ->where('sender_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if ($count) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Deleting Notification
    // ---- ------ -- --- -------- ------------

    public function deleteNotification(int $id)
    {
        $count = Notification::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('sender_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if ($count) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- --- -------- --- --- -------------
    // This Method Is For Checking For New Notifications
    // ---- ------ -- --- -------- --- --- -------------

    public function checkNewNotifications()
    {
        $count = Notification::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->where('seen', 0)
            ->count();
        return response()->json(['count' => $count], 200);
    }

    // ---- ------ -- --- ------- ------ -- --- -------------
    // This Method Is For Setting "Seen" To New Notifications
    // ---- ------ -- --- ------- ------ -- --- -------------

    public function setSeenNotifications()
    {
        $count = Notification::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->where('seen', 0)
            ->update([
                'seen' => 1,
                'updated_at' => now()
            ]);
        if ($count) {
            return response()->json(['success' => true], 200);
        }
    }

}