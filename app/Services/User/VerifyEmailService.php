<?php

namespace App\Services\User;

use App\Actions\LocationAction;
use App\Jobs\RegistrationSuccessJob;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerifyEmailService
{

    private const LOGIN_EXPIRY_HOURS = 3;

    // --- ---- -------- --- ------ -----
    // The Main Function For Verify Email
    // --- ---- -------- --- ------ -----

    public function handle($token, $request)
    {
        $verifiable = $this->checkTokenActivity($token);
        $this->destroyToken($token);
        $tokenId = $this->getTokenId($token);
        $this->createTokenEvent($tokenId, $request);
        $user = $this->getTokenOwner($verifiable);
        $this->activateTokenOwner($verifiable);
        $this->sendMail($user);
        $this->login($user);
        $location = $this->getLocation($request);
        $login_id = $this->createLoginInfo($user, $request, $location);
        $this->updateSession($login_id);
        return redirect()->route('index');
    }

    // ---- ------ -- --- -------- ----- --------
    // This Method Is For Checking Token Activity
    // ---- ------ -- --- -------- ----- --------

    private function checkTokenActivity($token)
    {
        $verifiable = PersonalAccessToken::where('token', $token)
            ->where('type', 'registration')
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

    // ---- ------ -- --- -------- ----- -----
    // This Method Is For Creating Token Event
    // ---- ------ -- --- -------- ----- -----

    private function createTokenEvent($tokenId, $request)
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

    private function getTokenOwner($verifiable)
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

    private function activateTokenOwner($verifiable)
    {
        User::where('id', $verifiable->user_id)
            ->update([
                'status' => 1,
                'activity' => 1,
                'email_verified_at' => now()
            ]);
    }

    // ---- ------ -- --- ------- ------------ ----- -- ----
    // This Method Is For Sending Confirmation Email To User
    // ---- ------ -- --- ------- ------------ ----- -- ----

    private function sendMail($user)
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

    private function login($user)
    {
        Auth::login($user);
    }

    // ---- ------ -- --- ------- --------
    // This Method Is For Getting Location
    // ---- ------ -- --- ------- --------

    private function getLocation($request)
    {
        $locationAction = new LocationAction();
        $location = $locationAction($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        return $location;
    }

    // ---- ------ -- --- -------- ----- ----
    // This Method Is For Creating Login Info
    // ---- ------ -- --- -------- ----- ----

    private function createLoginInfo($user, $request, $location)
    {
        return LoginInfo::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => $location,
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(self::LOGIN_EXPIRY_HOURS)
        ]);
    }

    // ---- ------ -- --- ------- ----- -- -- -------
    // This Method Is For Storing Login Id In Session
    // ---- ------ -- --- ------- ----- -- -- -------

    private function updateSession($login_id)
    {
        session()->put('login-id', $login_id->id);
    }

}