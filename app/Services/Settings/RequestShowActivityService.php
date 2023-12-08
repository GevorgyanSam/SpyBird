<?php

namespace App\Services\Settings;

use App\Models\User;

class RequestShowActivityService
{

    // --- ---- -------- --- ---- --------
    // The Main Function For Show Activity
    // --- ---- -------- --- ---- --------

    public function handle()
    {
        if ($this->showing()) {
            return response()->json(['reload' => true], 200);
        }
        $this->showActivity();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -------- ------ -- ---
    // This Method Is Designed To Check Whether The User Is Showing Activity Status Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -------- ------ -- ---

    private function showing()
    {
        return auth()->user()->activity;
    }

    // ---- ------ -- --- ------- --------
    // This Method Is For Showing Activity
    // ---- ------ -- --- ------- --------

    private function showActivity()
    {
        User::where('id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'activity' => 1,
                'updated_at' => now()
            ]);
    }

}