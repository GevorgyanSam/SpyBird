<?php

namespace App\Services\User;

use App\Jobs\VerifyEmailJob;
use App\Models\User;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use Illuminate\Support\Str;

class RegistrationService
{

    private const TOKEN_EXPIRY_HOURS = 1;

    // --- ---- -------- --- ---- ------------
    // The Main Function For User Registration
    // --- ---- -------- --- ---- ------------

    public function handle($request)
    {
        $this->validate($request);
        $user = $this->createUser($request);
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
            'name' => ['bail', 'required'],
            'email' => ['bail', 'required', 'email:rfc,dns,filter', 'unique:users'],
            'password' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'email' => [
                'enter valid :attribute address'
            ],
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
            'unique' => ':attribute already exists',
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- ------- ---- ---- ---- --------
    // This Method Is For Storing User Data Into Database
    // ---- ------ -- --- ------- ---- ---- ---- --------

    private function createUser($request)
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 0,
            'two_factor_authentication' => 0,
            'activity' => 0,
            'spy' => 0,
            'invisible' => 0,
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- ------- ---- ---------- ----- ---- --------
    // This Method Is For Storing User Verifiable Token Into Database
    // ---- ------ -- --- ------- ---- ---------- ----- ---- --------

    private function createToken($user)
    {
        return PersonalAccessToken::create([
            'user_id' => $user->id,
            'type' => 'registration',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::TOKEN_EXPIRY_HOURS)
        ]);
    }

    // ---- ------ -- --- ------- ----- -----
    // This Method Is For Storing Token Event
    // ---- ------ -- --- ------- ----- -----

    private function createTokenEvent($request, $token)
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

    private function sendMail($user, $token)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name,
            'token' => $token->token
        ];
        VerifyEmailJob::dispatch($jobData);
    }

}