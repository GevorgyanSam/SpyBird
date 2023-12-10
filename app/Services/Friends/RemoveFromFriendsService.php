<?php

namespace App\Services\Friends;

use App\Models\Friend;

class RemoveFromFriendsService
{

    // --- ---- -------- --- -------- ---- ------ ----
    // The Main Function For Removing From Friend List
    // --- ---- -------- --- -------- ---- ------ ----

    public function handle($id)
    {
        if ($this->selfRequest($id)) {
            return response()->json(['error' => true], 403);
        }
        $service = new GetFriendshipStatusService();
        $status = $service->handle($id);
        if ($this->missingRelationship($status)) {
            return response()->json(['error' => true], 403);
        }
        $check = $this->destroyFriendship($id);
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

    // ---- ------ -- -------- -- ----- -- ----- ------------ ------- ----- -- ---
    // This Method Is Designed To Check Is There Relationship Between Users Or Not
    // ---- ------ -- -------- -- ----- -- ----- ------------ ------- ----- -- ---

    private function missingRelationship($status)
    {
        return ($status != 'remove') ? true : false;
    }

    // ---- ------ -- --- -------- ---- ------ ----
    // This Method Is For Deleting From Friend List
    // ---- ------ -- --- -------- ---- ------ ----

    private function destroyFriendship($id)
    {
        return Friend::
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
    }

}