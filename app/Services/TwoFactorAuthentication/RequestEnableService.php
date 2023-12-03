<?php

namespace App\Services\TwoFactorAuthentication;

use App\Jobs\EnableTwoFactorAuthenticationJob;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use Illuminate\Support\Str;

class RequestEnableService
{

    protected const MAX_TOKEN_COUNT = 1;
    protected const TOKEN_EXPIRY_HOURS = 1;

    // --- ---- -------- --- ---------- -- ------ ---
    // The Main Function For Requesting To Enable 2FA
    // --- ---- -------- --- ---------- -- ------ ---

    public function handle($request)
    {
        if ($this->isEnable()) {
            return response()->json(['reload' => true], 200);
        }
        $count = $this->getOldTokens();
        if ($count >= self::MAX_TOKEN_COUNT) {
            return response()->json([], 429);
        }
        $token = $this->createToken();
        $this->createTokenEvent($token, $request);
        $this->sendMail($token);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ------ ---- --- ---- --- ------- ------- ---
    // This Method Is Designed To Verify That The User Has Already Enabled 2FA
    // ---- ------ -- -------- -- ------ ---- --- ---- --- ------- ------- ---

    protected function isEnable()
    {
        return auth()->user()->two_factor_authentication ? true : false;
    }

    // ---- ------ -- --- ------- --- ------
    // This Method Is For Getting Old Tokens
    // ---- ------ -- --- ------- --- ------

    protected function getOldTokens()
    {
        return PersonalAccessToken::where('user_id', auth()->user()->id)
            ->where('type', 'enable_two_factor_authentication')
            ->where('expires_at', '>', now())
            ->count();
    }

    // ---- ------ -- --- -------- -------- ------ ----- --- -------- ---
    // This Method Is For Creating Personal Access Token For Enabling 2FA
    // ---- ------ -- --- -------- -------- ------ ----- --- -------- ---

    protected function createToken()
    {
        return PersonalAccessToken::create([
            'user_id' => auth()->user()->id,
            'type' => 'enable_two_factor_authentication',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::TOKEN_EXPIRY_HOURS)
        ]);
    }

    // ---- ------ -- --- -------- -------- ------ ----- -----
    // This Method Is For Creating Personal Access Token Event
    // ---- ------ -- --- -------- -------- ------ ----- -----

    protected function createTokenEvent($token, $request)
    {
        PersonalAccessTokenEvent::create([
            'token_id' => $token->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    // ---- ------ -- --- ------- -----
    // This Method Is For Sending Email
    // ---- ------ -- --- ------- -----

    protected function sendMail($token)
    {
        $jobData = (object) [
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        EnableTwoFactorAuthenticationJob::dispatch($jobData);
    }

}