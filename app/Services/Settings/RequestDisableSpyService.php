<?php

namespace App\Services\Settings;

use App\Models\User;

class RequestDisableSpyService
{

    // --- ---- -------- --- --------- --- ----
    // The Main Function For Disabling Spy Mode
    // --- ---- -------- --- --------- --- ----

    public function handle()
    {
        if ($this->disabledSpy()) {
            return response()->json(['reload' => true], 200);
        }
        $this->disableSpy();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --- ---- -- ---
    // This Method Is Designed To Check Whether The User Is Already In Spy Mode Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --- ---- -- ---

    private function disabledSpy()
    {
        return !auth()->user()->spy;
    }

    // ---- ------ -- --- --------- --- ----
    // This Method Is For Disabling Spy Mode
    // ---- ------ -- --- --------- --- ----

    private function disableSpy()
    {
        User::where('id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'spy' => 0,
                'updated_at' => now()
            ]);
    }

}