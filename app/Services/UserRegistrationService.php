<?php

namespace App\Services;

use App\Jobs\VerifyEmailJob;
use App\Models\User;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use Illuminate\Support\Str;

class UserRegistrationService
{

    // --- ---- -------- --- ---- ------------
    // The Main Function For User Registration
    // --- ---- -------- --- ---- ------------

    public function register($request)
    {
        $user = $this->createUser($request);
        $token = $this->createToken($user);
        $this->createTokenEvent($request, $token);
        $this->sendMail($user, $token);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- ---- ---- ---- --------
    // This Method Is For Storing User Data Into Database
    // ---- ------ -- --- ------- ---- ---- ---- --------

    protected function createUser($request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 0,
            'two_factor_authentication' => 0,
            'activity' => 0,
            'invisible' => 0,
            'created_at' => now()
        ]);
        return $user;
    }

    // ---- ------ -- --- ------- ---- ---------- ----- ---- --------
    // This Method Is For Storing User Verifiable Token Into Database
    // ---- ------ -- --- ------- ---- ---------- ----- ---- --------

    protected function createToken($user)
    {
        $token = PersonalAccessToken::create([
            'user_id' => $user->id,
            'type' => 'registration',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(1)
        ]);
        return $token;
    }

    // ---- ------ -- --- ------- ----- -----
    // This Method Is For Storing Token Event
    // ---- ------ -- --- ------- ----- -----

    protected function createTokenEvent($request, $token)
    {
        PersonalAccessTokenEvent::create([
            'token_id' => $token->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    // ---- ------ -- --- ------- ---- --- ---- ------------
    // This Method Is For Sending Mail For User Verification
    // ---- ------ -- --- ------- ---- --- ---- ------------

    protected function sendMail($user, $token)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name,
            'token' => $token->token
        ];
        VerifyEmailJob::dispatch($jobData);
    }

}