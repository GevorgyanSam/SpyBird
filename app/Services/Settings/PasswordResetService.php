<?php

namespace App\Services\Settings;

use App\Jobs\PasswordResetJob;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use Illuminate\Support\Str;

class PasswordResetService
{

    private const MAX_TOKEN_COUNT = 2;
    private const TOKEN_EXPIRY_HOURS = 1;

    // --- ---- -------- --- -------- -----
    // The Main Function For Password Reset
    // --- ---- -------- --- -------- -----

    public function handle($request)
    {
        $count = $this->checkOldTokens();
        if ($count >= self::MAX_TOKEN_COUNT) {
            return response()->json(['success' => true], 200);
        }
        $token = $this->createToken();
        $this->createTokenEvent($token, $request);
        $this->sendMail($token);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- -------- -- ----- --- --- ----- -----
    // This Method Is Designed To Check For Old Token Count
    // ---- ------ -- -------- -- ----- --- --- ----- -----

    private function checkOldTokens()
    {
        return PersonalAccessToken::where('user_id', auth()->user()->id)
            ->where('type', 'password_reset')
            ->where('expires_at', '>', now())
            ->count();
    }

    // ---- ------ -- --- -------- -------- ------ -----
    // This Method Is For Creating Personal Access Token
    // ---- ------ -- --- -------- -------- ------ -----

    private function createToken()
    {
        return PersonalAccessToken::create([
            'user_id' => auth()->user()->id,
            'type' => 'password_reset',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::TOKEN_EXPIRY_HOURS)
        ]);
    }

    // ---- ------ -- --- -------- -------- ------ ----- -----
    // This Method Is For Creating Personal Access Token Event
    // ---- ------ -- --- -------- -------- ------ ----- -----

    private function createTokenEvent($token, $request)
    {
        PersonalAccessTokenEvent::create([
            'token_id' => $token->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    // ---- ------ -- --- ------- -------- ----- -----
    // This Method Is For Sending Password Reset Email
    // ---- ------ -- --- ------- -------- ----- -----

    private function sendMail($token)
    {
        $jobData = (object) [
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        PasswordResetJob::dispatch($jobData);
    }

}