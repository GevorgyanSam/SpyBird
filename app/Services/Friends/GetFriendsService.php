<?php

namespace App\Services\Friends;

use App\Models\Friend;

class GetFriendsService
{

    // --- ---- -------- --- ------- -------
    // The Main Function For Getting Friends
    // --- ---- -------- --- ------- -------

    public function handle()
    {
        $users = $this->getFriends();
        if ($this->missing($users)) {
            return response()->json(['empty' => true], 200);
        }
        $friends = $this->processData($users);
        return response()->json(['data' => $friends], 200);
    }

    // ---- ------ -- --- ------- ------- ---- --------
    // This Method Is For Getting Friends From Database
    // ---- ------ -- --- ------- ------- ---- --------

    private function getFriends()
    {
        return Friend::with('friendUser.latestLoginInfo', 'user.latestLoginInfo')
            ->where(function ($query) {
                $query->where('status', 1)
                    ->where('verified', 'accepted')
                    ->where('user_id', auth()->user()->id)
                    ->whereHas('friendUser', function ($friendUserQuery) {
                        $friendUserQuery->where('status', 1)
                            ->where('invisible', 0);
                    });
            })
            ->orWhere(function ($query) {
                $query->where('status', 1)
                    ->where('verified', 'accepted')
                    ->where('friend_user_id', auth()->user()->id)
                    ->whereHas('user', function ($userQuery) {
                        $userQuery->where('status', 1)
                            ->where('invisible', 0);
                    });
            })
            ->get();
    }

    // ---- ------ -- --- -------- -- ----- - ------- -- ---
    // This Method Is For Checking Is There A Friends Or Not
    // ---- ------ -- --- -------- -- ----- - ------- -- ---

    private function missing($users)
    {
        return !count($users) ? true : false;
    }

    // ---- ------ -- --- ---------- ---- ---- -- ----- --- --------
    // This Method Is For Processing User Data To Array For Response
    // ---- ------ -- --- ---------- ---- ---- -- ----- --- --------

    private function processData($users)
    {
        return $users;
    }

}