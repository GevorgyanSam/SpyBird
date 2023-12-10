<?php

namespace App\Services\Home;

use App\Models\LoginInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckAuthenticationService
{

    // --- ---- ------ --- -------- --------------
    // The Main Method For Checking Authentication
    // --- ---- ------ --- -------- --------------

    public function handle($request)
    {
        $loginId = $this->getLoginId();
        $loginInfo = $this->getLoginInfo($loginId);
        if ($this->loggedIn() && $this->active($loginInfo)) {
            if ($this->expired($loginInfo)) {
                $this->destroyLoginInfo($loginId);
                $this->clearCache($loginInfo);
                $this->logout();
                return response()->json(["reload" => true], 200);
            }
            if ($this->lockscreen($request)) {
                return response()->json(["reload" => true], 200);
            }
            return response()->json(["authenticated" => true], 200);
        }
        if ($this->loggedIn() && $this->inactive($loginInfo)) {
            $this->logout();
            return response()->json(["reload" => true], 200);
        }
        if ($this->loggedOut() && $this->active($loginInfo)) {
            $this->destroyLoginInfo($loginId);
            $this->clearCache($loginInfo);
            return response()->json(["reload" => true], 200);
        }
        return response()->json(["reload" => true], 200);
    }

    // ---- ------ -- --- ------- ----- --
    // This Method Is For Getting Login Id
    // ---- ------ -- --- ------- ----- --

    private function getLoginId()
    {
        return session()->get('login-id');
    }

    // ---- ------ -- --- ------- ----- ----
    // This Method Is For Getting Login Info
    // ---- ------ -- --- ------- ----- ----

    private function getLoginInfo($loginId)
    {
        return LoginInfo::findOrfail($loginId);
    }

    // ---- ------ -- --- -------- -- ---- ------ -- -------- -- ---
    // This Method Is For Checking If User Active In Database Or Not
    // ---- ------ -- --- -------- -- ---- ------ -- -------- -- ---

    private function active($loginInfo)
    {
        return $loginInfo->status;
    }

    // ---- ------ -- --- -------- -- ---- ------ -- -------- -- ---
    // This Method Is For Checking If User Active In Database Or Not
    // ---- ------ -- --- -------- -- ---- ------ -- -------- -- ---

    private function inactive($loginInfo)
    {
        return !$loginInfo->status;
    }

    // ---- ------ -- -------- -- ----- ---- ----- ---- ------- -- ---
    // This Method Is Designed To Check User Login Time Expired Or Not
    // ---- ------ -- -------- -- ----- ---- ----- ---- ------- -- ---

    private function expired($loginInfo)
    {
        return ($loginInfo->expires_at < now()) ? true : false;
    }

    // ---- ------ -- --- -------- ------ ----- ----
    // This Method Is For Deleting Active Login Info
    // ---- ------ -- --- -------- ------ ----- ----

    private function destroyLoginInfo($loginId)
    {
        LoginInfo::where('id', $loginId)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- -------- ---- ----- ----------- ---- -----
    // This Method Is For Clearing User Login Information From Cache
    // ---- ------ -- --- -------- ---- ----- ----------- ---- -----

    private function clearCache($loginInfo)
    {
        $cacheName = "device_" . $loginInfo->user_id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
    }

    // ---- ------ -- --- ------
    // This Method Is For Logout
    // ---- ------ -- --- ------

    private function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    // ---- ------ -- --- -------- ---- -- ---- ------ ---- -- ---
    // This Method Is For Checking User In Lock Screen Mode Or Not
    // ---- ------ -- --- -------- ---- -- ---- ------ ---- -- ---

    private function lockscreen($request)
    {
        $lockscreen = session()->get('lockscreen');
        $currentUrl = $request->header('referer');
        $lockscreenUrl = route('user.lockscreen');
        return ($lockscreen && ($currentUrl != $lockscreenUrl)) ? true : false;
    }

    // ---- ------ -- -------- -- ----- ---- ------ -- -- ---
    // This Method Is Designed To Check User Logged In Or Not
    // ---- ------ -- -------- -- ----- ---- ------ -- -- ---

    private function loggedIn()
    {
        return Auth::check();
    }

    // ---- ------ -- -------- -- ----- ---- ------ -- -- ---
    // This Method Is Designed To Check User Logged In Or Not
    // ---- ------ -- -------- -- ----- ---- ------ -- -- ---

    private function loggedOut()
    {
        return !Auth::check();
    }

}