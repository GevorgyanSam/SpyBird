<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\Notification;
use App\Services\Friends\GetFriendshipStatusService;
use App\Services\Friends\GetFriendshipService;
use App\Services\Friends\SendFriendRequestService;

class FriendsController extends Controller
{

    // ---- ------ -- --- ------- ------ ------- -- ----- ----
    // This Method Is For Sending Friend Request To Other User
    // ---- ------ -- --- ------- ------ ------- -- ----- ----

    public function sendFriendRequest(int $id, SendFriendRequestService $service)
    {
        return $service->handle($id);
    }

    // ---- ------ -- --- -------- ---- ------ ----
    // This Method Is For Removing From Friend List
    // ---- ------ -- --- -------- ---- ------ ----

    public function removeFromFriends(int $id)
    {
        if (auth()->user()->id == $id) {
            return response()->json(['error' => true], 403);
        }
        $service = new GetFriendshipStatusService();
        $status = $service->handle($id);
        if ($status != 'remove') {
            return response()->json(['error' => true], 403);
        }
        $check = Friend::
            where(function ($query) use ($id) {
                $query->where('status', 1)
                    ->where('verified', 'accepted')
                    ->where('user_id', auth()->user()->id)
                    ->where('friend_user_id', $id);
            })
            ->orWhere(function ($query) use ($id) {
                $query->where('status', 1)
                    ->where('verified', 'accepted')
                    ->where('friend_user_id', auth()->user()->id)
                    ->where('user_id', $id);
            })
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if ($check) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- -------- -- ------- --- ------ -------
    // This Method Is Intended To Confirm The Friend Request
    // ---- ------ -- -------- -- ------- --- ------ -------

    public function confirmFriendRequest(Request $request, int $id)
    {
        if (auth()->user()->id == $id) {
            return response()->json(['error' => true], 403);
        }
        $service = new GetFriendshipService();
        $friendship = $service->handle($id);
        if (empty($friendship) || $friendship->verified != 'pending') {
            return response()->json(['error' => true], 403);
        }
        $check = Friend::where('id', $friendship->id)
            ->update([
                'verified' => 'accepted',
                'updated_at' => now()
            ]);
        Notification::where('id', $request->input('notification'))
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if ($check) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -- -------- -- ------ --- ------ -------
    // This Method Is Intended To Reject The Friend Request
    // ---- ------ -- -------- -- ------ --- ------ -------

    public function rejectFriendRequest(Request $request, int $id)
    {
        if (auth()->user()->id == $id) {
            return response()->json(['error' => true], 403);
        }
        $service = new GetFriendshipService();
        $friendship = $service->handle($id);
        if (empty($friendship) || $friendship->verified != 'pending') {
            return response()->json(['error' => true], 403);
        }
        $check = Friend::where('id', $friendship->id)
            ->update([
                'verified' => 'rejected',
                'status' => 0,
                'updated_at' => now()
            ]);
        Notification::where('id', $request->input('notification'))
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if ($check) {
            return response()->json(['success' => true], 200);
        }
    }

}