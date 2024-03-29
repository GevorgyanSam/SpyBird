<?php

namespace App\Services\User;

use App\Actions\LocationAction;
use App\Jobs\AuthenticationCodeJob;
use App\Models\FailedLoginAttempt;
use App\Models\LoginInfo;
use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class LoginService
{

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const TWO_FACTOR_EXPIRY_MINUTES = 20;
    private const LOGIN_EXPIRY_HOURS = 3;

    // --- ---- -------- --- ---- -----
    // The Main Function For User Login
    // --- ---- -------- --- ---- -----

    public function handle($request)
    {
        $count = $this->failedLoginAttemptCheck($request);
        if ($count >= self::MAX_LOGIN_ATTEMPTS) {
            return response()->json(['errors' => ['title' => 'Too Many Requests', 'body' => 'Try Again After A While']], 429);
        }
        $this->validate($request);
        $credentials = $this->getUserCredentials($request);
        if (empty($credentials)) {
            return response()->json(['errors' => ['email' => ['Undefined Account']]], 422);
        }
        $check = Hash::check($request->input('password'), $credentials->password);
        if (!$check) {
            $this->createFailedLoginAttempt($request, $credentials);
            return response()->json(['errors' => ['password' => ['Wrong Password']]], 422);
        }
        if ($credentials->two_factor_authentication) {
            $code = $this->storeTwoFactorAuthenticationCode($credentials);
            $this->sendMail($credentials, $code);
            $this->saveTwoFactorCredentials($credentials);
            return response()->json(['two-factor' => true], 200);
        }
        $this->logoutOtherDevices($credentials);
        $this->login($credentials);
        $location = $this->getLocation($request);
        $login_id = $this->createLoginInfo($request, $location);
        $this->updateSession($login_id);
        $this->clearCache();
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

    // ---- ------ -- --- ----------- ---- ----
    // This Method Is For Validataing User Data
    // ---- ------ -- --- ----------- ---- ----

    private function validate($request)
    {
        $rules = [
            'email' => ['bail', 'required', 'email:rfc,dns,filter'],
            'password' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'email' => [
                'enter valid :attribute address'
            ],
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- ------- ---- -----------
    // This Method Is For Getting User Credentials
    // ---- ------ -- --- ------- ---- -----------

    private function getUserCredentials($request)
    {
        return User::where('email', $request->input('email'))
            ->where('status', 1)
            ->first();
    }

    // ---- ------ -- --- -------- ------ ----- -------
    // This Method Is For Creating Failed Login Attempt
    // ---- ------ -- --- -------- ------ ----- -------

    private function createFailedLoginAttempt($request, $credentials)
    {
        FailedLoginAttempt::create([
            'user_id' => $credentials->id,
            'type' => 'password',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- ------- --- ---- ---- --------
    // This Method Is For Storing 2FA Code Into Database
    // ---- ------ -- --- ------- --- ---- ---- --------

    private function storeTwoFactorAuthenticationCode($credentials)
    {
        $code = rand(10000000, 99999999);
        TwoFactorAuthentication::create([
            'user_id' => $credentials->id,
            'code' => $code,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(self::TWO_FACTOR_EXPIRY_MINUTES)
        ]);
        return $code;
    }

    // ---- ------ -- --- ------- ---- ---- --- ----
    // This Method Is For Sending Mail With 2FA Code
    // ---- ------ -- --- ------- ---- ---- --- ----

    private function sendMail($credentials, $code)
    {
        $jobData = (object) [
            'email' => $credentials->email,
            'name' => $credentials->name,
            'code' => $code
        ];
        AuthenticationCodeJob::dispatch($jobData);
    }

    // ---- ------ -- --- ------- --- ----------- -- -------
    // This Method Is For Storing 2FA Credentials In Session
    // ---- ------ -- --- ------- --- ----------- -- -------

    private function saveTwoFactorCredentials($credentials)
    {
        $data = (object) [
            'id' => $credentials->id,
            'name' => $credentials->name,
            'email' => $credentials->email,
            'avatar' => $credentials->avatar
        ];
        session()->put('credentials', $data);
    }

    // ---- ------ -- --- ------- --- -- ----- -------
    // This Method Is For Logging Out Of Other Devices
    // ---- ------ -- --- ------- --- -- ----- -------

    private function logoutOtherDevices($credentials)
    {
        LoginInfo::where('user_id', $credentials->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- -------- -- --------- ----- ----- -------- -- ---- ------- --- --- ---- ---- ---- --
    // This Method Is Designed To Terminate Other Users Sessions On This Account And Log That User Back In
    // ---- ------ -- -------- -- --------- ----- ----- -------- -- ---- ------- --- --- ---- ---- ---- --

    private function login($credentials)
    {
        Auth::login($credentials);
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

    // ---- ------ -- --- ------- ----- -- -- -------
    // This Method Is For Storing Login Id In Session
    // ---- ------ -- --- ------- ----- -- -- -------

    private function updateSession($login_id)
    {
        session()->put('login-id', $login_id->id);
    }

    // ---- ------ -- --- -------- ----- ------ -----
    // This Method Is For Clearing Cache Before Login
    // ---- ------ -- --- -------- ----- ------ -----

    private function clearCache()
    {
        $cacheName = "device_" . Auth::user()->id;
        Cache::forget($cacheName);
    }

}