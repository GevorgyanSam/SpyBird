<?php

namespace App\Services\TwoFactorAuthentication;

use App\Actions\LocationAction;
use App\Jobs\NewLoginJob;
use App\Models\FailedLoginAttempt;
use App\Models\LoginInfo;
use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class TwoFactorService
{

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOGIN_EXPIRY_HOURS = 3;

    // --- ---- -------- --- ---
    // The Main Function For 2FA
    // --- ---- -------- --- ---

    public function handle($request)
    {
        $count = $this->failedLoginAttemptCheck($request);
        if ($count >= self::MAX_LOGIN_ATTEMPTS) {
            return response()->json([], 429);
        }
        if ($this->missingCredentials()) {
            return response()->json(['reload' => true], 401);
        }
        $credentials = $this->getCredentials();
        $this->validate($request);
        $two_factor = $this->getTwoFactorCode($request, $credentials);
        if (empty($two_factor) || $this->inactive($two_factor) || $this->expired($two_factor)) {
            $this->createFailedLoginAttempt($credentials, $request);
            if (empty($two_factor)) {
                return response()->json(['errors' => ['code' => ['Wrong Code']]], 422);
            }
            if ($this->inactive($two_factor)) {
                return response()->json(['errors' => ['code' => ['This Code Was Used']]], 422);
            }
            if ($this->expired($two_factor)) {
                return response()->json(['errors' => ['code' => ['This Code Has Expired']]], 422);
            }
        }
        $this->destroyTwoFactorCode($two_factor);
        $user = $this->getUser($credentials);
        $this->logoutOtherDevices($user);
        $this->login($user);
        $location = $this->getLocation($request);
        $loginInfo = $this->createLoginInfo($request, $location);
        $this->forgetCredentials($loginInfo);
        $device = $this->getDevice($loginInfo);
        $date = $this->getDate($loginInfo);
        $this->sendMail($device, $location, $date);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- -------- --- ------ ----- --------
    // This Method Is For Checking For Failed Login Attempts
    // ---- ------ -- --- -------- --- ------ ----- --------

    private function failedLoginAttemptCheck($request)
    {
        return FailedLoginAttempt::where('ip', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->where('created_at', '>', now()->subHours(1))
            ->count();
    }

    // ---- ------ -- --- --------- -----------
    // This Method Is For Verifying Credentials
    // ---- ------ -- --- --------- -----------

    private function missingCredentials()
    {
        return session()->missing('credentials');
    }

    // ---- ------ -- --- ------- -----------
    // This Method Is For Getting Credentials
    // ---- ------ -- --- ------- -----------

    private function getCredentials()
    {
        return session()->get('credentials');
    }

    // ---- ------ -- --- ----------- ---- ----
    // This Method Is For Validataing User Data
    // ---- ------ -- --- ----------- ---- ----

    private function validate($request)
    {
        $rules = [
            'code' => ['bail', 'required', 'integer', 'digits:8'],
        ];
        $messages = [
            'required' => 'enter :attribute',
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- ------- --- ----
    // This Method Is For Getting 2FA Code
    // ---- ------ -- --- ------- --- ----

    private function getTwoFactorCode($request, $credentials)
    {
        return TwoFactorAuthentication::where('code', $request->input('code'))
            ->where('user_id', $credentials->id)
            ->first();
    }

    // ---- ------ -- -------- -- ----- ------- --- --- ---- -- -------- -- ---
    // This Method Is Designed To Check Whether The 2FA Code Is Inactive Or Not
    // ---- ------ -- -------- -- ----- ------- --- --- ---- -- -------- -- ---

    private function inactive($two_factor)
    {
        return (!$two_factor->status && $two_factor->updated_at != null) ? true : false;
    }

    // ---- ------ -- -------- -- ----- ------- --- --- ---- -- ------- -- ---
    // This Method Is Designed To Check Whether The 2FA Code Is Expired Or Not
    // ---- ------ -- -------- -- ----- ------- --- --- ---- -- ------- -- ---

    private function expired($two_factor)
    {
        return $two_factor->expires_at < now() ? true : false;
    }

    // ---- ------ -- --- -------- ------ ----- -------
    // This Method Is For Creating Failed Login Attempt
    // ---- ------ -- --- -------- ------ ----- -------

    private function createFailedLoginAttempt($credentials, $request)
    {
        FailedLoginAttempt::create([
            'user_id' => $credentials->id,
            'type' => 'two_factor_code',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- -------- --- ----
    // This Method Is For Deleting 2FA Code
    // ---- ------ -- --- -------- --- ----

    private function destroyTwoFactorCode($two_factor)
    {
        TwoFactorAuthentication::where('id', $two_factor->id)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- ------- ---- -- -----------
    // This Method Is For Getting User By Credentials
    // ---- ------ -- --- ------- ---- -- -----------

    private function getUser($credentials)
    {
        return User::findOrfail($credentials->id);
    }

    // ---- ------ -- --- ------- --- -- ----- -------
    // This Method Is For Logging Out Of Other Devices
    // ---- ------ -- --- ------- --- -- ----- -------

    private function logoutOtherDevices($user)
    {
        LoginInfo::where('user_id', $user->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- -----
    // This Method Is For Login
    // ---- ------ -- --- -----

    private function login($user)
    {
        Auth::login($user);
    }

    // ---- ------ -- --- ------- --------
    // This Method Is For Getting Location
    // ---- ------ -- --- ------- --------

    private function getLocation($request)
    {
        $locationAction = new LocationAction();
        $location = $locationAction($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        return $location;
    }

    // ---- ------ -- --- -------- ----- ----
    // This Method Is For Creating Login Info
    // ---- ------ -- --- -------- ----- ----

    private function createLoginInfo($request, $location)
    {
        return LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::LOGIN_EXPIRY_HOURS)
        ]);
    }

    // ---- ------ -- --- ---------- ----------- --- -------- -----
    // This Method Is For Forgetting Credentials And Clearing Cache
    // ---- ------ -- --- ---------- ----------- --- -------- -----

    private function forgetCredentials($loginInfo)
    {
        session()->forget('credentials');
        session()->put('login-id', $loginInfo->id);
        $cacheName = "device_" . Auth::user()->id;
        Cache::forget($cacheName);
    }

    // ---- ------ -- --- ------- ---- ------
    // This Method Is For Getting User Device
    // ---- ------ -- --- ------- ---- ------

    private function getDevice($loginInfo)
    {
        $agent = new Agent();
        $agent->setUserAgent($loginInfo->user_agent);
        $device = $agent->device();
        if (!$device || strtolower($device) == "webkit") {
            $device = $agent->platform();
        }
        return $device;
    }

    // ---- ------ -- --- ------- ---- -- ---- -------- ------
    // This Method Is For Getting Date In User Friendly Format
    // ---- ------ -- --- ------- ---- -- ---- -------- ------

    private function getDate($loginInfo)
    {
        return Carbon::parse($loginInfo->created_at)->format('d M H:i');
    }

    // ---- ------ -- --- ------- -----
    // This Method Is For Sending Email
    // ---- ------ -- --- ------- -----

    private function sendMail($device, $location, $date)
    {
        $jobData = (object) [
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'device' => $device,
            'location' => $location,
            'date' => $date
        ];
        NewLoginJob::dispatch($jobData);
    }

}