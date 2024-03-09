<?php

namespace App\Services\Friends;

class GetFriendshipStatusService
{

    // ---- ------ -- --- ------- ---------- ------ ------- -----
    // This Method Is For Getting Friendship Status Between Users
    // ---- ------ -- --- ------- ---------- ------ ------- -----

    public function handle($id)
    {
        $service = new GetFriendshipService();
        $friend = $service->handle($id);
        if ($this->missing($friend)) {
            return 'request';
        }
        if ($this->verified($friend)) {
            return 'remove';
        }
        return 'pending';
    }

    // ---- ------ -- -------- -- ----- --- ------- ----------
    // This Method Is Designed To Check For Missing Friendship
    // ---- ------ -- -------- -- ----- --- ------- ----------

    private function missing($friend)
    {
        return empty($friend) ? true : false;
    }

    // ---- ------ -- -------- -- ----- --- -------- ----------
    // This Method Is Designed To Check For Accepted Friendship
    // ---- ------ -- -------- -- ----- --- -------- ----------

    private function verified($friend)
    {
        return ($friend->verified == 'accepted') ? true : false;
    }

}