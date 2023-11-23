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
        $notifications = Notification::where([
            'user_id' => auth()->user()->id,
            'status' => 1
        ])->get();
        if (count($notifications)) {
            return response()->json(['data' => $notifications], 200);
        }
        return response()->json(['empty' => true], 200);
    }

}