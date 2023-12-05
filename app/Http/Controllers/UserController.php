<?php

namespace App\Http\Controllers;

use App\Actions\LocationAction;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Services\User\LockscreenService;
use App\Services\User\LoginService;
use App\Services\User\PasswordChangeService;
use App\Services\User\RegistrationService;
use App\Services\User\ResetService;
use App\Services\User\TokenService;
use App\Services\User\VerifyEmailService;

class UserController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Login Page View
    // ---- ------ -- --- ----- ---- ----

    public function login(Request $request)
    {
        if (session()->has('credentials')) {
            session()->forget('credentials');
        }
        Guest::create([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()
        ]);
        return view('users.login');
    }

    // ---- ------ -- --- ----- -----
    // This Method Is For Login Logic
    // ---- ------ -- --- ----- -----

    public function loginAuth(Request $request, LocationAction $locationAction, LoginService $service)
    {
        return $service->handle($request, $locationAction);
    }

    // ---- ------ -- --- ------------ ---- ----
    // This Method Is For Registration Page View
    // ---- ------ -- --- ------------ ---- ----

    public function register()
    {
        return view('users.register');
    }

    // ---- ------ -- --- ------------ -----
    // This Method Is For Registration Logic
    // ---- ------ -- --- ------------ -----

    public function registerAuth(Request $request, RegistrationService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- --------- ----- ----- ------------
    // This Method Is For Verifying Email After Registration
    // ---- ------ -- --- --------- ----- ----- ------------

    public function verifyEmail(string $token, Request $request, LocationAction $locationAction, VerifyEmailService $service)
    {
        return $service->handle($token, $request, $locationAction);
    }

    // ---- ------ -- --- -------- ----- ---- ----
    // This Method Is For Password Reset Page View
    // ---- ------ -- --- -------- ----- ---- ----

    public function reset()
    {
        return view('users.password-reset');
    }

    // ---- ------ -- --- ------- -------- ----- -----
    // This Method Is For Sending Password Reset Email
    // ---- ------ -- --- ------- -------- ----- -----

    public function resetAuth(Request $request, ResetService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ------ --- -------- ---- ----
    // This Method Is For Create New Password Page View
    // ---- ------ -- --- ------ --- -------- ---- ----

    public function token(string $token, Request $request, TokenService $service)
    {
        return $service->handle($token, $request);
    }

    // ---- ------ -- --- ------ --- -------- -----
    // This Method Is For Create New Password Logic
    // ---- ------ -- --- ------ --- -------- -----

    public function tokenAuth(Request $request, PasswordChangeService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ---- ------ ---- ----
    // This Method Is For Lock Screen Page View
    // ---- ------ -- --- ---- ------ ---- ----

    public function lockscreen()
    {
        if (session()->missing('lockscreen')) {
            return redirect()->route('index');
        }
        return view('users.lockscreen');
    }

    // ---- ------ -- --- ---- ------ -----
    // This Method Is For Lock Screen Logic
    // ---- ------ -- --- ---- ------ -----

    public function lockscreenAuth(Request $request, LockscreenService $service)
    {
        return $service->handle($request);
    }

}