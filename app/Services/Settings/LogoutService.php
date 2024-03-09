<?php

namespace App\Services\Settings;

use App\Models\LoginInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogoutService
{

    // --- ---- -------- --- ------
    // The Main Function For Logout
    // --- ---- -------- --- ------

    public function handle()
    {
        $this->destroyLoginInfo();
        $cacheName = $this->getCacheName();
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- -------- ------ ----- ----
    // This Method Is For Deleting Active Login Info
    // ---- ------ -- --- -------- ------ ----- ----

    private function destroyLoginInfo()
    {
        LoginInfo::where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- ------- ----- ----
    // This Method Is For Getting Cache Name
    // ---- ------ -- --- ------- ----- ----

    private function getCacheName()
    {
        return "device_" . auth()->user()->id;
    }

}