<?php

namespace App\Services\Friends;

use App\Models\Friend;
use App\Models\Notification;

class SendFriendRequestService
{

    // --- ---- ------ --- ------- ------ ------- -- ----- ----
    // The Main Method For Sending Friend Request To Other User
    // --- ---- ------ --- ------- ------ ------- -- ----- ----

    public function handle($id)
    {
        $service = new GetFriendshipStatusService();
        $status = $service->handle($id);
        if ($this->existsRelationship($status)) {
            return response()->json(['error' => true], 403);
        }
        $this->sendRequest($id);
        $this->createNotification($id);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- -- ----- ------------ ------- ----- -- ---
    // This Method Is Designed To Check Is There Relationship Between Users Or Not
    // ---- ------ -- -------- -- ----- -- ----- ------------ ------- ----- -- ---

    private function existsRelationship($status)
    {
        return ($status != 'request') ? true : false;
    }

    // ---- ------ -- --- ------- ------ ------- -- ----
    // This Method Is For Sending Friend Request To User
    // ---- ------ -- --- ------- ------ ------- -- ----

    private function sendRequest($id)
    {
        Friend::create([
            'user_id' => auth()->user()->id,
            'friend_user_id' => $id,
            'verified' => 'pending',
            'status' => 1,
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Creating Notification
    // ---- ------ -- --- -------- ------------

    private function createNotification($id)
    {
        Notification::create([
            "user_id" => $id,
            "sender_id" => auth()->user()->id,
            "type" => "friend_request",
            "content" => "sent you a friend request",
            "seen" => 0,
            "status" => 1,
            "created_at" => now()
        ]);
    }

}