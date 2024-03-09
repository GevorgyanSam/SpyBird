<?php

namespace App\Services\Settings;

use App\Models\LoginInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class GetLoginHistoryService
{

    private const CACHE_EXPIRY_HOURS = 1;

    // ---- ------ -- --- ------- ----- ------- -------- --------- -----
    // This Method Is For Getting Login History (Device, Location, Date)
    // ---- ------ -- --- ------- ----- ------- -------- --------- -----

    public function handle(): array
    {
        $cacheName = $this->getCacheName();
        if (Cache::has($cacheName)) {
            $devices = Cache::get($cacheName);
            return $devices;
        }
        $devices = [];
        $loginInfo = $this->getLoginInfo();
        foreach ($loginInfo as $item) {
            array_push($devices, [
                'link' => $this->getLink($item),
                'status' => $item->status,
                'platform' => $this->getDevice($item),
                'location' => $item->location,
                'date' => $this->getDate($item),
                'deleted_at' => $item->deleted_at
            ]);
        }
        Cache::put($cacheName, $devices, now()->addHours(self::CACHE_EXPIRY_HOURS));
        return $devices;
    }

    // ---- ------ -- --- ------- ----- ----
    // This Method Is For Getting Cache Name
    // ---- ------ -- --- ------- ----- ----

    private function getCacheName()
    {
        return "device_" . auth()->user()->id;
    }

    // ---- ------ -- --- ------- ----- ----
    // This Method Is For Getting Login Info
    // ---- ------ -- --- ------- ----- ----

    private function getLoginInfo()
    {
        return LoginInfo::where('user_id', auth()->user()->id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();
    }

    // ---- ------ -- --- ------- ---- ------
    // This Method Is For Getting User Device
    // ---- ------ -- --- ------- ---- ------

    private function getDevice($item)
    {
        $agent = new Agent();
        $agent->setUserAgent($item->user_agent);
        $device = $agent->device();
        if (!$device || strtolower($device) == "webkit") {
            $device = $agent->platform();
        }
        return $device;
    }

    // ---- ------ -- --- ------- ----
    // This Method Is For Getting Data
    // ---- ------ -- --- ------- ----

    private function getDate($item)
    {
        return Carbon::parse($item->created_at)->format('d M H:i');
    }

    // ---- ------ -- --- ------- ------ ------ ----
    // This Method Is For Getting Delete Device Link
    // ---- ------ -- --- ------- ------ ------ ----

    private function getLink($item)
    {
        return route("delete-device", ["id" => $item->id]);
    }

}