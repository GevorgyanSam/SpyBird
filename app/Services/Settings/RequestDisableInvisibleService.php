<?php

namespace App\Services\Settings;

use App\Models\User;

class RequestDisableInvisibleService
{

    // --- ---- -------- --- --------- --------- ----
    // The Main Function For Disabling Invisible Mode
    // --- ---- -------- --- --------- --------- ----

    public function handle()
    {
        if ($this->disabledInvisible()) {
            return response()->json(['reload' => true], 200);
        }
        $this->disableInvisible();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --------- ---- -- ---
    // This Method Is Designed To Check Whether The User Is Already In Invisible Mode Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --------- ---- -- ---

    private function disabledInvisible()
    {
        return !auth()->user()->invisible;
    }

    // ---- ------ -- --- --------- --------- ----
    // This Method Is For Disabling Invisible Mode
    // ---- ------ -- --- --------- --------- ----

    private function disableInvisible()
    {
        User::where('id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'invisible' => 0,
                'updated_at' => now()
            ]);
    }

}