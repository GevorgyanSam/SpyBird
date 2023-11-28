<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\Notification;

class FriendsController extends Controller
{

    // ---- ------ -- --- ------- ---------- ------ ------- -----
    // This Method Is For Getting Friendship Status Between Users
    // ---- ------ -- --- ------- ---------- ------ ------- -----

    public static function getFriendshipStatus(int $id)
    {
        $friend = Friend::
            where(function ($query) use ($id) {
                $query->where('status', 1)
                    ->where('user_id', auth()->user()->id)
                    ->where('friend_user_id', $id);
            })
            ->orWhere(function ($query) use ($id) {
                $query->where('status', 1)
                    ->where('friend_user_id', auth()->user()->id)
                    ->where('user_id', $id);
            })
            ->first();
        if (empty($friend)) {
            return 'request';
        }
        if ($friend->verified == 'accepted') {
            return 'remove';
        }
        return 'pending';
    }

    // ---- ------ -- --- ------- ------ ------- -- ----- ----
    // This Method Is For Sending Friend Request To Other User
    // ---- ------ -- --- ------- ------ ------- -- ----- ----

    public function sendFriendRequest(int $id)
    {
        if (auth()->user()->id == $id) {
            return response()->json(['error' => true], 403);
        }
        $status = $this->getFriendshipStatus($id);
        if ($status != 'request') {
            return response()->json(['error' => true], 403);
        }
        Friend::create([
            'user_id' => auth()->user()->id,
            'friend_user_id' => $id,
            'verified' => 'pending',
            'status' => 1,
            'created_at' => now()
        ]);
        Notification::create([
            "user_id" => $id,
            "sender_id" => auth()->user()->id,
            "type" => "friend_request",
            "content" => "sent you a friend request",
            "seen" => 0,
            "status" => 1,
            "created_at" => now()
        ]);
        return response()->json(['success' => true], 200);
    }

}