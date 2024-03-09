<?php

namespace App\Services\Settings;

use App\Models\LoginInfo;
use Illuminate\Support\Facades\Cache;

class DeleteDeviceService
{

    // --- ---- -------- --- -------- ------
    // The Main Function For Deleting Device
    // --- ---- -------- --- -------- ------

    public function handle($id)
    {
        $device = $this->getLoginInfo($id);
        if ($this->exists($device)) {
            return response()->json([], 404);
        }
        $this->destroyDevice($id);
        $cacheName = $this->getCacheName();
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- ----- ----
    // This Method Is For Getting Login Info
    // ---- ------ -- --- ------- ----- ----

    private function getLoginInfo($id)
    {
        return LoginInfo::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('status', 0)
            ->where('deleted_at', null)
            ->get();
    }

    // ---- ------ -- -------- -- ----- ------ ------ -- ---
    // This Method Is Designed To Check Device Exists Or Not
    // ---- ------ -- -------- -- ----- ------ ------ -- ---

    private function exists($device)
    {
        return !$device->count() ? true : false;
    }

    // ---- ------ -- --- -------- ------ ---- ------ -------
    // This Method Is For Deleting Device From System History
    // ---- ------ -- --- -------- ------ ---- ------ -------

    private function destroyDevice($id)
    {
        LoginInfo::where('id', $id)
            ->update([
                'deleted_at' => now()
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