<?php

namespace App\Services\TwoFactorAuthentication;

use App\Jobs\EnableTwoFactorAuthenticationConfirmationJob;
use App\Models\BackupCode;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class EnableService
{

    // --- ---- -------- --- -------- ---
    // The Main Function For Enabling 2FA
    // --- ---- -------- --- -------- ---

    public function handle($request, $token)
    {
        $verifiable = $this->getToken($token);
        $this->destroyToken($token);
        $tokenId = $this->getTokenId($token);
        $this->createTokenEvent($tokenId, $request);
        $user = $this->getTokenOwner($verifiable);
        $this->logoutOtherDevices($user);
        $this->enableTwoFactor($user);
        $backupCodes = $this->createBackupCodes($user);
        $this->sendMail($user, $backupCodes);
        return redirect()->route('user.login');
    }

    // ---- ------ -- --- ------- -------- ------ -----
    // This Method Is For Getting Personal Access Token
    // ---- ------ -- --- ------- -------- ------ -----

    private function getToken($token)
    {
        $verifiable = PersonalAccessToken::where('token', $token)
            ->where('type', 'enable_two_factor_authentication')
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

    private function destroyToken($token)
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

    private function getTokenId($token)
    {
        return PersonalAccessToken::where('token', $token)
            ->value('id');
    }

    // ---- ------ -- --- -------- -------- ------ ----- -----
    // This Method Is For Creating Personal Access Token Event
    // ---- ------ -- --- -------- -------- ------ ----- -----

    private function createTokenEvent($tokenId, $request)
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

    private function getTokenOwner($verifiable)
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

    private function logoutOtherDevices($user)
    {
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $cacheName = "device_" . $user->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
    }

    // ---- ------ -- --- -------- ---
    // This Method Is For Enabling 2FA
    // ---- ------ -- --- -------- ---

    private function enableTwoFactor($user)
    {
        User::where(['id' => $user->id])->update([
            'two_factor_authentication' => 1,
            'updated_at' => now()
        ]);
    }

    // ---- ------ -- --- -------- ------ ----- --- ---
    // This Method Is For Creating Backup Codes For 2FA
    // ---- ------ -- --- -------- ------ ----- --- ---

    private function createBackupCodes($user)
    {
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
        return $backupCodes;
    }

    // ---- ------ -- --- ------- ------------ -----
    // This Method Is For Sending Confirmation Email
    // ---- ------ -- --- ------- ------------ -----

    private function sendMail($user, $backupCodes)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name,
            'codes' => $backupCodes
        ];
        EnableTwoFactorAuthenticationConfirmationJob::dispatch($jobData);
    }

}