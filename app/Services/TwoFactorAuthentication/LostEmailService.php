<?php

namespace App\Services\TwoFactorAuthentication;

use App\Actions\LocationAction;
use App\Jobs\NewLoginJob;
use App\Models\BackupCode;
use App\Models\FailedLoginAttempt;
use App\Models\LoginInfo;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class LostEmailService
{

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOGIN_EXPIRY_HOURS = 3;

    // --- ---- -------- --- ---- -----
    // The Main Function For Lost Email
    // --- ---- -------- --- ---- -----

    public function handle($request)
    {
        $count = $this->failedLoginAttemptCheck($request);
        if($count >= self::MAX_LOGIN_ATTEMPTS) {
            return response()->json([], 429);
        }
        if($this->missingCredentials()) {
            return response()->json(['reload' => true], 401);
        }
        $credentials = $this->getCredentials();
        $this->vaildate($request);
        $backup_code = $this->getBackupCode($request, $credentials);
        if(empty($backup_code) || $this->inactive($backup_code)) {
            $this->createFailedLoginAttempt($request, $credentials);
            if(empty($backup_code)) {
                return response()->json(['errors' => ['code' => ['Wrong Code']]], 422);
            }
            if($this->inactive($backup_code)) {
                return response()->json(['errors' => ['code' => ['This Code Was Used']]], 422);
            }
        }
        $this->destroyBackupCode($backup_code);
        $user = $this->getUser($credentials);

        // ----- ----- ----- ----- ----- ----- -----

        LoginInfo::where('user_id', $user->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        Auth::login($user);
        $locationAction = new LocationAction();
        $location = $locationAction($request->ip());
        if(isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name.', '.$location->city;
        }
        $loginInfo = LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::LOGIN_EXPIRY_HOURS)
        ]);
        session()->forget('credentials');
        session()->put('login-id', $loginInfo->id);
        $cacheName = "device_".Auth::user()->id;
        Cache::forget($cacheName);
        $agent = new Agent();
        $agent->setUserAgent($loginInfo->user_agent);
        $device = $agent->device();
        if(!$device || strtolower($device) == "webkit") {
            $device = $agent->platform();
        }
        $date = Carbon::parse($loginInfo->created_at)->format('d M H:i');
        $jobData = (object)[
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'device' => $device,
            'location' => $location,
            'date' => $date
        ];
        NewLoginJob::dispatch($jobData);
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

    private function vaildate($request)
    {
        $rules = [
            'code' => ['bail', 'required', 'integer', 'digits:8'],
        ];
        $messages = [
            'required' => 'enter :attribute',
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- ------- ------ ----
    // This Method Is For Getting Backup Code
    // ---- ------ -- --- ------- ------ ----

    private function getBackupCode($request, $credentials)
    {
        return BackupCode::where('code', $request->input('code'))
            ->where('user_id', $credentials->id)
            ->first();
    }

    // ---- ------ -- --- -------- ------ ----- -------
    // This Method Is For Creating Failed Login Attempt
    // ---- ------ -- --- -------- ------ ----- -------

    private function createFailedLoginAttempt($request, $credentials)
    {
        FailedLoginAttempt::create([
            'user_id' => $credentials->id,
            'type' => 'backup_code',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()
        ]);
    }

    // ---- ------ -- -------- -- ----- ------- --- ------ ---- -- -------- -- ---
    // This Method Is Designed To Check Whether The Backup Code Is Inactive Or Not
    // ---- ------ -- -------- -- ----- ------- --- ------ ---- -- -------- -- ---

    private function inactive($backup_code)
    {
        return (!$backup_code->status && $backup_code->updated_at != null) ? true : false;
    }

    // ---- ------ -- --- -------- ------ ----
    // This Method Is For Deleting Backup Code
    // ---- ------ -- --- -------- ------ ----

    private function destroyBackupCode($backup_code)
    {
        BackupCode::where('id', $backup_code->id)
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

}