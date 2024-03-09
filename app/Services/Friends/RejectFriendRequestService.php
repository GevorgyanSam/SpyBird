<?php

namespace App\Services\Friends;

use App\Models\Friend;
use App\Models\Notification;

class RejectFriendRequestService
{

    // ---- ------ -- -------- -- ------ --- ------ -------
    // This Method Is Intended To Reject The Friend Request
    // ---- ------ -- -------- -- ------ --- ------ -------

    public function handle($request, $id)
    {
        if ($this->selfRequest($id)) {
            return response()->json(['error' => true], 403);
        }
        $service = new GetFriendshipService();
        $friendship = $service->handle($id);
        if ($this->missing($friendship) || $this->notPending($friendship)) {
            return response()->json(['error' => true], 403);
        }
        $check = $this->reject($friendship);
        $this->destroyNotification($request);
        if ($check) {
            return response()->json(['success' => true], 200);
        }
    }

    // ---- ------ -------- ------- ------ ------- -- --------
    // This Method Prevents Sending Friend Request To Yourself
    // ---- ------ -------- ------- ------ ------- -- --------

    private function selfRequest($id)
    {
        return (auth()->user()->id == $id) ? true : false;
    }

    // ---- ------ -- -------- -- ----- - ---------- -- ---
    // This Method Is Checking Is There A Friendship Or Not
    // ---- ------ -- -------- -- ----- - ---------- -- ---

    private function missing($friendship)
    {
        return empty($friendship) ? true : false;
    }

    // ---- ------ -- -------- ------- ---------- -- ------- -- ---
    // This Method Is Checking Current Friendship Is Pending Or Not
    // ---- ------ -- -------- ------- ---------- -- ------- -- ---

    private function notPending($friendship)
    {
        return ($friendship->verified != 'pending') ? true : false;
    }

    // ---- ------ -- --- --------- ------ -------
    // This Method Is For Rejecting Friend Request
    // ---- ------ -- --- --------- ------ -------

    private function reject($friendship)
    {
        return Friend::where('id', $friendship->id)
            ->update([
                'verified' => 'rejected',
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- -------- ------ ------- ------------
    // This Method Is For Deleting Friend Request Notification
    // ---- ------ -- --- -------- ------ ------- ------------

    private function destroyNotification($request)
    {
        Notification::where('id', $request->input('notification'))
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

}