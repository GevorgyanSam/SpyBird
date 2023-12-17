<?php

namespace App\Services\Block;

use App\Models\BlockedUser;

class BlockUserService
{

    // ---- ------ -- -------- -- ----- ----
    // This Method Is Designed To Block User
    // ---- ------ -- -------- -- ----- ----

    public function handle($id)
    {
        if ($this->blockedBy($id)) {
            return response()->json(['error' => true], 401);
        }
        $service = new GetBlockedRelationshipService();
        $status = $service->handle($id);
        if ($this->blocked($status)) {
            return response()->json(['error' => true], 401);
        }
        $this->block($id);
    }

    // ---- ------ -- -------- -- ----- -- ---- ---- ------- ------- -- ---
    // This Method Is Designed To Check Is This User Already Blocked Or Not
    // ---- ------ -- -------- -- ----- -- ---- ---- ------- ------- -- ---

    private function blockedBy($id)
    {
        $blocked = BlockedUser::where('user_id', $id)
            ->where('blocked_user_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        return $blocked ? true : false;
    }

    // ---- ------ -- -------- -- ----- -- ---- ---- ------- -- ---
    // This Method Is Designed To Check Is This User Blocked Or Not
    // ---- ------ -- -------- -- ----- -- ---- ---- ------- -- ---

    private function blocked($status)
    {
        return ($status == 'block') ? false : true;
    }

    // ---- ------ -- -------- -- ----- ----
    // This Method Is Designed To Block User
    // ---- ------ -- -------- -- ----- ----

    private function block($id)
    {
        BlockedUser::create([
            'user_id' => auth()->user()->id,
            'blocked_user_id' => $id,
            'status' => 1,
            'created_at' => now()
        ]);
    }

}