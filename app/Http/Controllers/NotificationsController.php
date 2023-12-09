<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\Notifications\CheckNewNotificationsService;
use App\Services\Notifications\ClearNotificationsService;
use App\Services\Notifications\DeleteNotificationService;
use App\Services\Notifications\GetNewNotificationsService;
use App\Services\Notifications\GetNotificationsService;

class NotificationsController extends Controller
{

    // ---- ------ -- --- ------- -------------
    // This Method Is For Getting Notifications
    // ---- ------ -- --- ------- -------------

    public function getNotifications(GetNotificationsService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ------- --- -------------
    // This Method Is For Getting New Notifications
    // ---- ------ -- --- ------- --- -------------

    public function getNewNotifications(GetNewNotificationsService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- -------- --- -------------
    // This Method Is For Clearing All Notifications
    // ---- ------ -- --- -------- --- -------------

    public function clearNotifications(ClearNotificationsService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Deleting Notification
    // ---- ------ -- --- -------- ------------

    public function deleteNotification(int $id, DeleteNotificationService $service)
    {
        return $service->handle($id);
    }

    // ---- ------ -- --- -------- --- --- -------------
    // This Method Is For Checking For New Notifications
    // ---- ------ -- --- -------- --- --- -------------

    public function checkNewNotifications(CheckNewNotificationsService $service)
    {
        return $service->handle();
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