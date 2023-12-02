<?php

namespace App\Services;

use App\Jobs\RegistrationSuccessJob;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerifyEmailService
{

    protected const LOGIN_EXPIRY_HOURS = 3;

    // --- ---- -------- --- ------ -----
    // The Main Function For Verify Email
    // --- ---- -------- --- ------ -----

    public function handle($token, $request, $locationAction)
    {
        $verifiable = $this->checkTokenActivity($token);
        $this->destroyToken($token);
        $tokenId = $this->getTokenId($token);
        $this->createTokenEvent($tokenId, $request);
        $user = $this->getTokenOwner($verifiable);
        $this->activateTokenOwner($verifiable);
        $this->sendMail($user);
        $this->login($user, $request, $locationAction);
        return redirect()->route('index');
    }

    // ---- ------ -- --- -------- ----- --------
    // This Method Is For Checking Token Activity
    // ---- ------ -- --- -------- ----- --------

    protected function checkTokenActivity($token)
    {
        $verifiable = PersonalAccessToken::where(['token' => $token, 'type' => 'registration', 'status' => 1])->first();
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
        return PersonalAccessToken::where('token', $token)->value('id');
    }

    // ---- ------ -- --- -------- ----- -----
    // This Method Is For Creating Token Event
    // ---- ------ -- --- -------- ----- -----

    protected function createTokenEvent($tokenId, $request)
    {
        PersonalAccessTokenEvent::create([
            'token_id' => $tokenId,
            'type' => 'usage',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    // ---- ------ -- -------- -- --- --- ----- -- --- -----
    // This Method Is Designed To Get The Owner Of The Token
    // ---- ------ -- -------- -- --- --- ----- -- --- -----

    protected function getTokenOwner($verifiable)
    {
        $user = User::find($verifiable->user_id);
        if (!empty($user->email_verified_at)) {
            abort(404);
        }
        return $user;
    }

    // ---- ------ -- --- ---------- --- ----- -- -----
    // This Method Is For Activating The Owner Of Token
    // ---- ------ -- --- ---------- --- ----- -- -----

    protected function activateTokenOwner($verifiable)
    {
        User::where(['id' => $verifiable->user_id])->update([
            'status' => 1,
            'activity' => 1,
            'email_verified_at' => now()
        ]);
    }

    // ---- ------ -- --- ------- ------------ ----- -- ----
    // This Method Is For Sending Confirmation Email To User
    // ---- ------ -- --- ------- ------------ ----- -- ----

    protected function sendMail($user)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name
        ];
        RegistrationSuccessJob::dispatch($jobData);
    }

    // ---- ------ -- --- ---- ----- ----- ----------
    // This Method Is For User Login After Activation
    // ---- ------ -- --- ---- ----- ----- ----------

    protected function login($user, $request, $locationAction)
    {
        Auth::login($user);
        $location = $locationAction($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        $login_id = LoginInfo::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::LOGIN_EXPIRY_HOURS)
        ]);
        session()->put('login-id', $login_id->id);
    }

}