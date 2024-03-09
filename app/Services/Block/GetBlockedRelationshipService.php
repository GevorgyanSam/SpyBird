<?php

namespace App\Services\Block;

use App\Models\BlockedUser;

class GetBlockedRelationshipService
{

    // ---- ------ -- -------- -- ------ ------- ---- ---- -- ------- -- --- ------ -- ----
    // This Method Is Designed To Return Whether This User Is Blocked By The Logged In User
    // ---- ------ -- -------- -- ------ ------- ---- ---- -- ------- -- --- ------ -- ----

    public function handle($id)
    {
        $user = $this->getBlockedUser($id);
        return $this->blocked($user) ? 'unblock' : 'block';
    }

    // ---- ------ -- -------- -- --- ------- ---- ---- --------
    // This Method Is Designed To Get Blocked User From Database
    // ---- ------ -- -------- -- --- ------- ---- ---- --------

    private function getBlockedUser($id)
    {
        return BlockedUser::where('user_id', auth()->user()->id)
            ->where('blocked_user_id', $id)
            ->where('status', 1)
            ->count();
    }

    // ---- ------ -- --- -------- -- ---- ---- ------- -- ---
    // This Method Is For Checking Is This User Blocked Or Not
    // ---- ------ -- --- -------- -- ---- ---- ------- -- ---

    private function blocked($user)
    {
        return $user ? true : false;
    }

}