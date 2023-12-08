<?php

namespace App\Services\Settings;

use App\Models\User;

class RequestEnableInvisibleService
{

    // --- ---- -------- --- -------- --------- ----
    // The Main Function For Enabling Invisible Mode
    // --- ---- -------- --- -------- --------- ----

    public function handle()
    {
        if ($this->invisible()) {
            return response()->json(['reload' => true], 200);
        }
        $this->enableInvisible();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --------- ---- -- ---
    // This Method Is Designed To Check Whether The User Is Already In Invisible Mode Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --------- ---- -- ---

    private function invisible()
    {
        return auth()->user()->invisible;
    }

    // ---- ------ -- --- -------- --------- ----
    // This Method Is For Enabling Invisible Mode
    // ---- ------ -- --- -------- --------- ----

    private function enableInvisible()
    {
        User::where('id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'invisible' => 1,
                'updated_at' => now()
            ]);
    }

}