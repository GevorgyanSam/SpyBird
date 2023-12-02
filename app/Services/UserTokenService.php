<?php

namespace App\Services;

use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\User;

class UserTokenService
{

    // --- ---- -------- --- ------ --- -------- ---- ----
    // The Main Function For Create New Password Page View
    // --- ---- -------- --- ------ --- -------- ---- ----

    public function handle($token, $request)
    {
        $verifiable = $this->checkTokenActivity($token);
        $this->destroyToken($token);
        $tokenId = $this->getTokenId($token);
        $this->createTokenEvent($request, $tokenId);
        $user = $this->getTokenOwner($verifiable);
        $this->updateIfInactive($user);
        return view('users.token', ['user' => $user, 'token' => $token]);
    }

    // ---- ------ -- --- -------- ----- --------
    // This Method Is For Checking Token Activity
    // ---- ------ -- --- -------- ----- --------

    protected function checkTokenActivity($token)
    {
        $verifiable = PersonalAccessToken::where('token', $token)
            ->where('type', 'password_reset')
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

    // ---- ------ -- --- ------------ -----
    // This Method Is For Deactivating Token
    // ---- ------ -- --- ------------ -----

    protected function destroyToken($token)
    {
        PersonalAccessToken::where(['token' => $token])->update([
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

    // ---- ------ -- --- -------- ----- -----
    // This Method Is For Creating Token Event
    // ---- ------ -- --- -------- ----- -----

    protected function createTokenEvent($request, $tokenId)
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
        $user = User::where(['id' => $verifiable->user_id, 'status' => 1])->orWhereNull('email_verified_at')->first();
        if (empty($user)) {
            abort(404);
        }
        return $user;
    }

    // ---- ------ -- --- ---------- ---- -- -- -- --- ------
    // This Method Is For Activating User If He Is Not Active
    // ---- ------ -- --- ---------- ---- -- -- -- --- ------

    protected function updateIfInactive($user)
    {
        if (empty($user->email_verified_at)) {
            User::where('id', $user->id)->update([
                'status' => 1,
                'activity' => 1,
                'email_verified_at' => now()
            ]);
        }
    }

}