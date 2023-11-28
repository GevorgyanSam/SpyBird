<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginInfo;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Index Page View
    // ---- ------ -- --- ----- ---- ----

    public function index()
    {
        $devices = SettingsController::getLoginHistory();
        return view('pages.index', ['devices' => $devices]);
    }

    // ---- ------ -- --- ---- ---- ----
    // This Method Is For Chat Page View
    // ---- ------ -- --- ---- ---- ----

    public function room(int $id)
    {
        $devices = SettingsController::getLoginHistory();
        return view('pages.room', ['devices' => $devices]);
    }

    // ---- ------ -- --- -------- --------------
    // This Method Is For Checking Authentication
    // ---- ------ -- --- -------- --------------

    public function checkAuthentication(Request $request)
    {
        $login_id = session()->get('login-id');
        $loginInfo = LoginInfo::findOrfail($login_id);
        if (Auth::check() && $loginInfo->status) {
            if ($loginInfo->expires_at < now()) {
                LoginInfo::where(['id' => $login_id])->update([
                    'status' => 0,
                    'updated_at' => now()
                ]);
                $cacheName = "device_" . $loginInfo->user_id;
                if (Cache::has($cacheName)) {
                    Cache::forget($cacheName);
                }
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                return response()->json(["reload" => true], 200);
            }
            $lockscreen = session()->get('lockscreen');
            if ($lockscreen) {
                if ($request->header('referer') != route('user.lockscreen')) {
                    return response()->json(["reload" => true], 200);
                }
            }
            return response()->json(["authenticated" => true], 200);
        }
        if (Auth::check() && !$loginInfo->status) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return response()->json(["reload" => true], 200);
        }
        if (!Auth::check() && $loginInfo->status) {
            LoginInfo::where(['id' => $login_id])->update([
                'status' => 0,
                'updated_at' => now()
            ]);
            $cacheName = "device_" . $loginInfo->user_id;
            if (Cache::has($cacheName)) {
                Cache::forget($cacheName);
            }
            return response()->json(["reload" => true], 200);
        }
        return response()->json(["reload" => true], 200);
    }

    // ---- ------ -- --- ------- ------------ ------- -----
    // This Method Is For Getting Relationship Between Users
    // ---- ------ -- --- ------- ------------ ------- -----

    public function getRelationship(int $id)
    {
        $response = (object) [];
        $response->friend = FriendsController::getFriendshipStatus($id);
        return response()->json($response, 200);
    }

}