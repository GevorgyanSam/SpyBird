<?php

namespace App\Services\Settings;

use App\Jobs\AccountTerminationConfirmationJob;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\User;
use App\Models\UserDataHistory;
use Illuminate\Support\Facades\Cache;

class AccountTerminationService
{

    // --- ---- -------- --- ------- -----------
    // The Main Function For Account Termination
    // --- ---- -------- --- ------- -----------

    public function handle($request, $token)
    {
        $verifiable = $this->getToken($token);
        $this->destroyToken($token);
        $tokenId = $this->getTokenId($token);
        $this->createTokenEvent($tokenId, $request);
        $user = $this->getUser($verifiable);
        $this->logoutOtherDevices($user);
        $this->destroyUser($user);
        $this->createUserDataHistory($user);
        $this->sendMail($user);
        return redirect()->route('user.login');
    }

    // ---- ------ -- --- ------- ------- ----------- -----
    // This Method Is For Getting Account Termination Token
    // ---- ------ -- --- ------- ------- ----------- -----

    private function getToken($token)
    {
        $verifiable = PersonalAccessToken::where('token', $token)
            ->where('type', 'account_termination')
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

    // ---- ------ -- --- -------- -------- ------ -----
    // This Method Is For Deleting Personal Access Token
    // ---- ------ -- --- -------- -------- ------ -----

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

    private function getUser($verifiable)
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

    // ---- ------ -- --- -------- ---- -------
    // This Method Is For Deleting User Account
    // ---- ------ -- --- -------- ---- -------

    private function destroyUser($user)
    {
        User::where('id', $user->id)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
    }

    // ---- ------ -- --- -------- ---- ---- -------
    // This Method Is For Creating User Data History
    // ---- ------ -- --- -------- ---- ---- -------

    private function createUserDataHistory($user)
    {
        UserDataHistory::create([
            'user_id' => $user->id,
            'type' => 'account_termination',
            'from' => 1,
            'to' => 0,
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- ------- ------------ ----- -- ------- -----------
    // This Method Is For Sending Confirmation Email Of Account Termination
    // ---- ------ -- --- ------- ------------ ----- -- ------- -----------

    private function sendMail($user)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name
        ];
        AccountTerminationConfirmationJob::dispatch($jobData);
    }

}