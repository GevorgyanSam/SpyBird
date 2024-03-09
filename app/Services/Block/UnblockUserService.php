<?php

namespace App\Services\Block;

use App\Models\BlockedUser;

class UnblockUserService
{

    // ---- ------ -- -------- -- ------- ----
    // This Method Is Designed To Unblock User
    // ---- ------ -- -------- -- ------- ----

    public function handle($id)
    {
        $service = new GetBlockedRelationshipService();
        $status = $service->handle($id);
        if ($this->unblocked($status)) {
            return response()->json(['error' => true], 401);
        }
        $this->unblock($id);
    }

    // ---- ------ -- -------- -- ----- -- ---- ---- ------- -- ---
    // This Method Is Designed To Check Is This User Blocked Or Not
    // ---- ------ -- -------- -- ----- -- ---- ---- ------- -- ---

    private function unblocked($status)
    {
        return ($status == 'unblock') ? false : true;
    }

    // ---- ------ -- -------- -- ----- ----
    // This Method Is Designed To Block User
    // ---- ------ -- -------- -- ----- ----

    private function unblock($id)
    {
        BlockedUser::where('user_id', auth()->user()->id)
            ->where('blocked_user_id', $id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

}