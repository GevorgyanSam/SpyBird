<?php

namespace App\Services\Friends;

use App\Models\Friend;
use Illuminate\Support\Carbon;

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
            ->orderByDesc('created_at')
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
        return $users->map(function ($user) {
            if ($user->user_id == auth()->user()->id) {
                return $this->fetchAssocData($user->friendUser);
            } else {
                return $this->fetchAssocData($user->user);
            }
        });
    }

    // ---- ------ -- --- ------------ ---- -- -----
    // This Method Is For Transforming Data To Array
    // ---- ------ -- --- ------------ ---- -- -----

    private function fetchAssocData($user)
    {
        $avatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
        $date = $user->latestLoginInfo->updated_at ? Carbon::parse($user->latestLoginInfo->updated_at)->format('d M H:i') : null;
        if (!$user->activity) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $avatar,
                'hidden' => true
            ];
        }
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $avatar,
            'status' => $user->latestLoginInfo->status,
            'updated_at' => $date
        ];
    }

}