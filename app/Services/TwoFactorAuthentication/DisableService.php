<?php

namespace App\Services\TwoFactorAuthentication;

use App\Jobs\DisableTwoFactorAuthenticationConfirmationJob;
use App\Models\BackupCode;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DisableService
{

    // --- ---- -------- --- --------- ---
    // The Main Function For Disabling 2FA
    // --- ---- -------- --- --------- ---

    public function handle($request, $token)
    {
        $verifiable = $this->getToken($token);
        $this->destroyToken($token);
        $tokenId = $this->getTokenId($token);
        $this->createTokenEvent($tokenId, $request);
        $user = $this->getTokenOwner($verifiable);
        $this->logoutOtherDevices($user);
        $this->disableTwoFactor($user);
        $this->destroyBackupCodes($user);
        $this->destroyTwoFactorCodes($user);
        $this->sendMail($user);
        return redirect()->route('user.login');
    }

    // ---- ------ -- --- ------- -------- ------ -----
    // This Method Is For Getting Personal Access Token
    // ---- ------ -- --- ------- -------- ------ -----

    protected function getToken($token)
    {
        $verifiable = PersonalAccessToken::where('token', $token)
            ->where('type', 'disable_two_factor_authentication')
            ->where('status', 1)
            ->first();
        if (empty($verifiable)) {
            abort(404);
        }
        if ($verifiable->expires_at <= now()) {
            abort(404);
        }
        return $verifiable;
    }

    // ---- ------ -- --- -------- -----
    // This Method Is For Inactive Token
    // ---- ------ -- --- -------- -----

    protected function destroyToken($token)
    {
        PersonalAccessToken::where('token', $token)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- ------- ----- --
    // This Method Is For Getting Token Id
    // ---- ------ -- --- ------- ----- --

    protected function getTokenId($token)
    {
        return PersonalAccessToken::where('token', $token)
            ->value('id');
    }

    // ---- ------ -- --- -------- -------- ------ ----- -----
    // This Method Is For Creating Personal Access Token Event
    // ---- ------ -- --- -------- -------- ------ ----- -----

    protected function createTokenEvent($tokenId, $request)
    {
        PersonalAccessTokenEvent::create([
            'token_id' => $tokenId,
            'type' => 'usage',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    // ---- ------ -- --- ------- ----- -----
    // This Method Is For Getting Token Owner
    // ---- ------ -- --- ------- ----- -----

    protected function getTokenOwner($verifiable)
    {
        $user = User::where('id', $verifiable->user_id)
            ->where('status', 1)
            ->first();
        if (empty($user)) {
            abort(404);
        }
        return $user;
    }

    // ---- ------ -- --- ------- --- -- ----- -------
    // This Method Is For Logging Out Of Other Devices
    // ---- ------ -- --- ------- --- -- ----- -------

    protected function logoutOtherDevices($user)
    {
        LoginInfo::where('user_id', $user->id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        $cacheName = "device_" . $user->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
    }

    // ---- ------ -- --- --------- ---
    // This Method Is For Disabling 2FA
    // ---- ------ -- --- --------- ---

    protected function disableTwoFactor($user)
    {
        User::where('id', $user->id)
            ->update([
                'two_factor_authentication' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- ------------ ------ -----
    // This Method Is For Inactivating Backup Codes
    // ---- ------ -- --- ------------ ------ -----

    protected function destroyBackupCodes($user)
    {
        BackupCode::where('user_id', $user->id)
            ->where('status', 1)
            ->where('updated_at', null)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- ------------ --- -----
    // This Method Is For Inactivating 2FA Codes
    // ---- ------ -- --- ------------ --- -----

    protected function destroyTwoFactorCodes($user)
    {
        TwoFactorAuthentication::where('user_id', $user->id)
            ->where('status', 1)
            ->where('updated_at', null)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- ------- ------------ -----
    // This Method Is For Sending Confirmation Email
    // ---- ------ -- --- ------- ------------ -----

    protected function sendMail($user)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name
        ];
        DisableTwoFactorAuthenticationConfirmationJob::dispatch($jobData);
    }

}