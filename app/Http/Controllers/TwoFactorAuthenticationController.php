<?php

namespace App\Http\Controllers;

use App\Actions\LocationAction;
use App\Jobs\NewLoginJob;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LoginInfo;
use App\Models\BackupCode;
use App\Models\FailedLoginAttempt;
use App\Models\TwoFactorAuthentication;
use App\Services\TwoFactorAuthentication\DisableService;
use App\Services\TwoFactorAuthentication\EnableService;
use App\Services\TwoFactorAuthentication\LostEmailViewService;
use App\Services\TwoFactorAuthentication\RequestDisableService;
use App\Services\TwoFactorAuthentication\RequestEnableService;
use App\Services\TwoFactorAuthentication\TwoFactorViewService;
use Jenssegers\Agent\Agent;

class TwoFactorAuthenticationController extends Controller
{

    // ---- ------ -- --- ---------- -- ------ ---
    // This Method Is For Requesting To Enable 2FA
    // ---- ------ -- --- ---------- -- ------ ---

    public function requestEnableTwoFactor(Request $request, RequestEnableService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- -------- ---
    // This Method Is For Enabling 2FA
    // ---- ------ -- --- -------- ---

    public function enableTwoFactor(Request $request, string $token, EnableService $service)
    {
        return $service->handle($request, $token);
    }

    // ---- ------ -- --- ---------- -- ------- ---
    // This Method Is For Requesting To Disable 2FA
    // ---- ------ -- --- ---------- -- ------- ---

    public function requestDisableTwoFactor(Request $request, RequestDisableService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- --------- ---
    // This Method Is For Disabling 2FA
    // ---- ------ -- --- --------- ---

    public function disableTwoFactor(Request $request, string $token, DisableService $service)
    {
        return $service->handle($request, $token);
    }

    // ---- ------ -- --- --- ------ -------------- ---- ----
    // This Method Is For Two Factor Authentication Page View
    // ---- ------ -- --- --- ------ -------------- ---- ----

    public function twoFactor(TwoFactorViewService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- --- ------ -------------- -----
    // This Method Is For Two Factor Authentication Logic
    // ---- ------ -- --- --- ------ -------------- -----

    public function twoFactorAuth(Request $request, LocationAction $locationAction)
    {
        $failed_login_attempts = FailedLoginAttempt::where([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ])->where('created_at', '>', now()->subHours(1))->count();
        if ($failed_login_attempts >= 5) {
            return response()->json([], 429);
        }
        if (!session()->has('credentials')) {
            return response()->json(['reload' => true], 401);
        }
        $credentials = session()->get('credentials');
        $rules = [
            'code' => ['bail', 'required', 'integer', 'digits:8'],
        ];
        $messages = [
            'required' => 'enter :attribute',
        ];
        $request->validate($rules, $messages);
        $two_factor = TwoFactorAuthentication::where(['code' => $request->input('code'), 'user_id' => $credentials->id])->first();
        if (empty($two_factor) || !$two_factor->status || $two_factor->expires_at < now()) {
            FailedLoginAttempt::create([
                'user_id' => $credentials->id,
                'type' => 'two_factor_code',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);
            if (empty($two_factor)) {
                return response()->json(['errors' => ['code' => ['Wrong Code']]], 422);
            }
            if (!$two_factor->status && $two_factor->updated_at != null) {
                return response()->json(['errors' => ['code' => ['This Code Was Used']]], 422);
            }
            if ($two_factor->expires_at < now()) {
                return response()->json(['errors' => ['code' => ['This Code Has Expired']]], 422);
            }
        }
        TwoFactorAuthentication::where(['id' => $two_factor->id])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $user = User::findOrfail($credentials->id);
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        Auth::login($user);
        $location = $locationAction($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        $loginInfo = LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(3)
        ]);
        session()->forget('credentials');
        session()->put('login-id', $loginInfo->id);
        $cacheName = "device_" . Auth::user()->id;
        Cache::forget($cacheName);
        $agent = new Agent();
        $agent->setUserAgent($loginInfo->user_agent);
        $device = $agent->device();
        if (!$device || strtolower($device) == "webkit") {
            $device = $agent->platform();
        }
        $date = Carbon::parse($loginInfo->created_at)->format('d M H:i');
        $jobData = (object) [
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'device' => $device,
            'location' => $location,
            'date' => $date
        ];
        NewLoginJob::dispatch($jobData);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---- ----- -------------- ---- ----
    // This Method Is For Lost Email Authentication Page View
    // ---- ------ -- --- ---- ----- -------------- ---- ----

    public function lostEmail(LostEmailViewService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ---- ----- -------------- -----
    // This Method Is For Lost Email Authentication Logic
    // ---- ------ -- --- ---- ----- -------------- -----

    public function lostEmailAuth(Request $request, LocationAction $locationAction)
    {
        $failed_login_attempts = FailedLoginAttempt::where([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ])->where('created_at', '>', now()->subHours(1))->count();
        if ($failed_login_attempts >= 5) {
            return response()->json([], 429);
        }
        if (!session()->has('credentials')) {
            return response()->json(['reload' => true], 401);
        }
        $credentials = session()->get('credentials');
        $rules = [
            'code' => ['bail', 'required', 'integer', 'digits:8'],
        ];
        $messages = [
            'required' => 'enter :attribute',
        ];
        $request->validate($rules, $messages);
        $backup_code = BackupCode::where(['code' => $request->input('code'), 'user_id' => $credentials->id])->first();
        if (empty($backup_code) || !$backup_code->status) {
            FailedLoginAttempt::create([
                'user_id' => $credentials->id,
                'type' => 'backup_code',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);
            if (empty($backup_code)) {
                return response()->json(['errors' => ['code' => ['Wrong Code']]], 422);
            }
            if (!$backup_code->status && $backup_code->updated_at != null) {
                return response()->json(['errors' => ['code' => ['This Code Was Used']]], 422);
            }
        }
        BackupCode::where(['id' => $backup_code->id])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $user = User::findOrfail($credentials->id);
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        Auth::login($user);
        $location = $locationAction($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        $loginInfo = LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(3)
        ]);
        session()->forget('credentials');
        session()->put('login-id', $loginInfo->id);
        $cacheName = "device_" . Auth::user()->id;
        Cache::forget($cacheName);
        $agent = new Agent();
        $agent->setUserAgent($loginInfo->user_agent);
        $device = $agent->device();
        if (!$device || strtolower($device) == "webkit") {
            $device = $agent->platform();
        }
        $date = Carbon::parse($loginInfo->created_at)->format('d M H:i');
        $jobData = (object) [
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'device' => $device,
            'location' => $location,
            'date' => $date
        ];
        NewLoginJob::dispatch($jobData);
        return response()->json(['success' => true], 200);
    }

}