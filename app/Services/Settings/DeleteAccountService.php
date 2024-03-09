<?php

namespace App\Services\Settings;

use App\Jobs\AccountTerminationJob;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use Illuminate\Support\Str;

class DeleteAccountService
{

    private const MAX_TOKEN_COUNT = 1;
    private const TOKEN_EXPIRY_HOURS = 1;

    // --- ---- -------- --- -------- -------
    // The Main Function For Deleting Account
    // --- ---- -------- --- -------- -------

    public function handle($request)
    {
        $count = $this->getOldTokens();
        if ($count >= self::MAX_TOKEN_COUNT) {
            return response()->json(['error' => true], 429);
        }
        $token = $this->createToken();
        $this->createTokenEvent($token, $request);
        $this->sendMail($token);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- --- ------
    // This Method Is For Getting Old Tokens
    // ---- ------ -- --- ------- --- ------

    private function getOldTokens()
    {
        return PersonalAccessToken::where('user_id', auth()->user()->id)
            ->where('type', 'account_termination')
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
            'type' => 'account_termination',
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

    // ---- ------ -- --- ------- -----
    // This Method Is For Sending Email
    // ---- ------ -- --- ------- -----

    private function sendMail($token)
    {
        $jobData = (object) [
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        AccountTerminationJob::dispatch($jobData);
    }

}