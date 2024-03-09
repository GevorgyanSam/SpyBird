<?php

namespace App\Services\Settings;

use App\Models\User;

class RequestHideActivityService
{

    // --- ---- -------- --- ---- --------
    // The Main Function For Hide Activity
    // --- ---- -------- --- ---- --------

    public function handle()
    {
        if ($this->hidden()) {
            return response()->json(['reload' => true], 200);
        }
        $this->hideActivity();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -------- ------ -- ---
    // This Method Is Designed To Check Whether The User Is Showing Activity Status Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -------- ------ -- ---

    private function hidden()
    {
        return !auth()->user()->activity;
    }

    // ---- ------ -- --- ------ --------
    // This Method Is For Hiding Activity
    // ---- ------ -- --- ------ --------

    private function hideActivity()
    {
        User::where('id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'activity' => 0,
                'updated_at' => now()
            ]);
    }

}