<?php

namespace App\Services\Friends;

use App\Models\Friend;

class GetFriendshipService
{

    // --- ---- -------- --- ------- ----------
    // The Main Function For Getting Friendship
    // --- ---- -------- --- ------- ----------

    public function handle($id)
    {
        $friend = $this->getRelationship($id);
        return $friend;
    }

    // ---- ------ -- --- ------- ---------- ------- -----
    // This Method Is For Getting Friendship Between Users
    // ---- ------ -- --- ------- ---------- ------- -----

    private function getRelationship($id)
    {
        return Friend::
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
    }

}