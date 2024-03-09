<?php

namespace App\Services\Settings;

use App\Models\User;

class RequestEnableSpyService
{

    // --- ---- -------- --- -------- --- ----
    // The Main Function For Enabling Spy Mode
    // --- ---- -------- --- -------- --- ----

    public function handle()
    {
        if ($this->enabledSpy()) {
            return response()->json(['reload' => true], 200);
        }
        $this->enableSpy();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --- ---- -- ---
    // This Method Is Designed To Check Whether The User Is Already In Spy Mode Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- -- ------- -- --- ---- -- ---

    private function enabledSpy()
    {
        return auth()->user()->spy;
    }

    // ---- ------ -- --- -------- --- ----
    // This Method Is For Enabling Spy Mode
    // ---- ------ -- --- -------- --- ----

    private function enableSpy()
    {
        User::where('id', auth()->user()->id)
            ->where('status', 1)
            ->update([
                'spy' => 1,
                'updated_at' => now()
            ]);
    }

}