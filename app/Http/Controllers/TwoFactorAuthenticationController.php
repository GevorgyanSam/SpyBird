<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Mail\EnableTwoFactorAuthentication;
use App\Mail\DisableTwoFactorAuthentication;
use App\Mail\EnableTwoFactorAuthenticationConfirmation;
use App\Mail\DisableTwoFactorAuthenticationConfirmation;
use App\Mail\NewLogin;
use App\Models\User;
use App\Models\LoginInfo;
use App\Models\BackupCode;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\FailedLoginAttempt;
use App\Models\TwoFactorAuthentication;
use Jenssegers\Agent\Agent;

class TwoFactorAuthenticationController extends Controller
{

    // ---- ------ -- --- ---------- -- ------ ---
    // This Method Is For Requesting To Enable 2FA
    // ---- ------ -- --- ---------- -- ------ ---

    public function requestEnableTwoFactor(Request $request)
    {
        if (auth()->user()->two_factor_authentication) {
            return response()->json(['reload' => true], 200);
        }
        $old_tokens = PersonalAccessToken::where(['user_id' => auth()->user()->id, 'type' => 'enable_two_factor_authentication'])->where('expires_at', '>', now())->count();
        if ($old_tokens >= 1) {
            return response()->json([], 429);
        }
        $token = PersonalAccessToken::create([
            'user_id' => auth()->user()->id,
            'type' => 'enable_two_factor_authentication',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(1)
        ]);
        PersonalAccessTokenEvent::create([
            'token_id' => $token->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $emailData = (object) [
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        Mail::to(auth()->user()->email)->send(new EnableTwoFactorAuthentication($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- -------- ---
    // This Method Is For Enabling 2FA
    // ---- ------ -- --- -------- ---

    public function enableTwoFactor(Request $request, string $token)
    {
        $verifiable = PersonalAccessToken::where(['token' => $token, 'type' => 'enable_two_factor_authentication', 'status' => 1])->first();
        if (empty($verifiable)) {
            abort(404);
        }
        if ($verifiable->expires_at <= now()) {
            abort(404);
        }
        PersonalAccessToken::where(['token' => $token])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $tokenId = PersonalAccessToken::where('token', $token)->value('id');
        PersonalAccessTokenEvent::create([
            'token_id' => $tokenId,
            'type' => 'usage',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $user = User::where(['id' => $verifiable->user_id, 'status' => 1])->first();
        if (empty($user)) {
            abort(404);
        }
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $cacheName = "device_" . $user->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        User::where(['id' => $user->id])->update([
            'two_factor_authentication' => 1,
            'updated_at' => now()
        ]);
        $backupCodes = [];
        for ($i = 0; $i < 6; $i++) {
            $code = rand(10000000, 99999999);
            array_push($backupCodes, $code);
            BackupCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'status' => 1,
                'created_at' => now()
            ]);
        }
        $emailData = (object) [
            'name' => $user->name,
            'codes' => $backupCodes
        ];
        Mail::to($user->email)->send(new EnableTwoFactorAuthenticationConfirmation($emailData));
        return redirect()->route('user.login');
    }

    // ---- ------ -- --- ---------- -- ------- ---
    // This Method Is For Requesting To Disable 2FA
    // ---- ------ -- --- ---------- -- ------- ---

    public function requestDisableTwoFactor(Request $request)
    {
        if (!auth()->user()->two_factor_authentication) {
            return response()->json(['reload' => true], 200);
        }
        $old_tokens = PersonalAccessToken::where(['user_id' => auth()->user()->id, 'type' => 'disable_two_factor_authentication'])->where('expires_at', '>', now())->count();
        if ($old_tokens >= 1) {
            return response()->json([], 429);
        }
        $token = PersonalAccessToken::create([
            'user_id' => auth()->user()->id,
            'type' => 'disable_two_factor_authentication',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(1)
        ]);
        PersonalAccessTokenEvent::create([
            'token_id' => $token->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $emailData = (object) [
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        Mail::to(auth()->user()->email)->send(new DisableTwoFactorAuthentication($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- --------- ---
    // This Method Is For Disabling 2FA
    // ---- ------ -- --- --------- ---

    public function disableTwoFactor(Request $request, string $token)
    {
        $verifiable = PersonalAccessToken::where(['token' => $token, 'type' => 'disable_two_factor_authentication', 'status' => 1])->first();
        if (empty($verifiable)) {
            abort(404);
        }
        if ($verifiable->expires_at <= now()) {
            abort(404);
        }
        PersonalAccessToken::where(['token' => $token])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $tokenId = PersonalAccessToken::where('token', $token)->value('id');
        PersonalAccessTokenEvent::create([
            'token_id' => $tokenId,
            'type' => 'usage',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $user = User::where(['id' => $verifiable->user_id, 'status' => 1])->first();
        if (empty($user)) {
            abort(404);
        }
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $cacheName = "device_" . $user->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        User::where(['id' => $user->id])->update([
            'two_factor_authentication' => 0,
            'updated_at' => now()
        ]);
        BackupCode::where(['user_id' => $user->id, 'status' => 1, 'updated_at' => null])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        TwoFactorAuthentication::where(['user_id' => $user->id, 'status' => 1, 'updated_at' => null])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $emailData = (object) [
            'name' => $user->name
        ];
        Mail::to($user->email)->send(new DisableTwoFactorAuthenticationConfirmation($emailData));
        return redirect()->route('user.login');
    }

    // ---- ------ -- --- --- ------ -------------- ---- ----
    // This Method Is For Two Factor Authentication Page View
    // ---- ------ -- --- --- ------ -------------- ---- ----

    public function twoFactor()
    {
        if (!session()->has('credentials')) {
            return redirect()->route('user.login');
        }
        $credentials = session()->get('credentials');
        if (isset($credentials->visited)) {
            session()->forget('credentials');
            return redirect()->route('user.login');
        }
        $credentials->visited = true;
        $position = Str::position($credentials->email, '@');
        $replacement = substr($credentials->email, 1, $position - 2);
        $masked_email = str_replace($replacement, '*****', $credentials->email);
        $credentials->masked = $masked_email;
        return view('users.two-factor', ['credentials' => $credentials]);
    }

    // ---- ------ -- --- --- ------ -------------- -----
    // This Method Is For Two Factor Authentication Logic
    // ---- ------ -- --- --- ------ -------------- -----

    public function twoFactorAuth(Request $request)
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
        $loginInfo = LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
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
        $location = LocationController::find($loginInfo->ip);
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        $emailData = (object) [
            'name' => Auth::user()->name,
            'device' => $device,
            'location' => $location,
            'date' => $date
        ];
        Mail::to(Auth::user()->email)->send(new NewLogin($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---- ----- -------------- ---- ----
    // This Method Is For Lost Email Authentication Page View
    // ---- ------ -- --- ---- ----- -------------- ---- ----

    public function lostEmail()
    {
        return view('users.lost-email');
    }

    // ---- ------ -- --- ---- ----- -------------- -----
    // This Method Is For Lost Email Authentication Logic
    // ---- ------ -- --- ---- ----- -------------- -----

    public function lostEmailAuth(Request $request)
    {
        return response()->json(['success' => true], 200);
    }

}