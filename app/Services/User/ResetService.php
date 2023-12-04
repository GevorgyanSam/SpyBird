<?php

namespace App\Services\User;

use App\Jobs\PasswordResetJob;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\User;
use Illuminate\Support\Str;

class ResetService
{

    private const TOKEN_EXPIRY_HOURS = 1;
    private const MAX_TOKEN_COUNT = 2;

    // --- ---- -------- --- -------- -----
    // The Main Function For Password Reset
    // --- ---- -------- --- -------- -----

    public function handle($request)
    {
        $this->validate($request);
        $user = $this->getUser($request);
        if (empty($user)) {
            return response()->json(['errors' => ['email' => ['Undefined Account']]], 422);
        }
        $old_tokens = $this->getOldTokens($user);
        if ($old_tokens >= self::MAX_TOKEN_COUNT) {
            return response()->json(['success' => true], 200);
        }
        $token = $this->createToken($user);
        $this->createTokenEvent($request, $token);
        $this->sendMail($user, $token);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ----------- ---- ----
    // This Method Is For Validataing User Data
    // ---- ------ -- --- ----------- ---- ----

    private function validate($request)
    {
        $rules = [
            'email' => ['bail', 'required', 'email:rfc,dns,filter'],
        ];
        $messages = [
            'email' => [
                'enter valid :attribute address'
            ],
            'required' => 'enter :attribute',
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- ------- ----
    // This Method Is For Getting User
    // ---- ------ -- --- ------- ----

    private function getUser($request)
    {
        return User::where('email', $request->input('email'))
            ->where('status', 1)
            ->orWhereNull('email_verified_at')
            ->first();
    }

    // ---- ------ -- --- ------- --- ------
    // This Method Is For Getting Old Tokens
    // ---- ------ -- --- ------- --- ------

    private function getOldTokens($user)
    {
        return PersonalAccessToken::where('user_id', $user->id)
            ->where('type', 'password_reset')
            ->where('expires_at', '>', now())
            ->count();
    }

    // ---- ------ -- --- -------- -------- ------ -----
    // This Method Is For Creating Personal Access Token
    // ---- ------ -- --- -------- -------- ------ -----

    private function createToken($user)
    {
        return PersonalAccessToken::create([
            'user_id' => $user->id,
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

    private function createTokenEvent($request, $token)
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

    private function sendMail($user, $token)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name,
            'token' => $token->token
        ];
        PasswordResetJob::dispatch($jobData);
    }

}