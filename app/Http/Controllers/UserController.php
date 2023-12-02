<?php

namespace App\Http\Controllers;

use App\Actions\LocationAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guest;
use App\Models\FailedLoginAttempt;
use App\Services\UserLoginService;
use App\Services\UserPasswordChangeService;
use App\Services\UserRegistrationService;
use App\Services\UserResetService;
use App\Services\UserTokenService;
use App\Services\VerifyEmailService;

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

    public function loginAuth(Request $request, LocationAction $locationAction, UserLoginService $service)
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

    public function registerAuth(Request $request, UserRegistrationService $service)
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

    public function resetAuth(Request $request, UserResetService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ------ --- -------- ---- ----
    // This Method Is For Create New Password Page View
    // ---- ------ -- --- ------ --- -------- ---- ----

    public function token(string $token, Request $request, UserTokenService $service)
    {
        return $service->handle($token, $request);
    }

    // ---- ------ -- --- ------ --- -------- -----
    // This Method Is For Create New Password Logic
    // ---- ------ -- --- ------ --- -------- -----

    public function tokenAuth(Request $request, UserPasswordChangeService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ---- ------ ---- ----
    // This Method Is For Lock Screen Page View
    // ---- ------ -- --- ---- ------ ---- ----

    public function lockscreen()
    {
        if (!session()->has('lockscreen')) {
            return redirect()->route('index');
        }
        return view('users.lockscreen');
    }

    // ---- ------ -- --- ---- ------ -----
    // This Method Is For Lock Screen Logic
    // ---- ------ -- --- ---- ------ -----

    public function lockscreenAuth(Request $request)
    {
        $failed_login_attempts = FailedLoginAttempt::where([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ])->where('created_at', '>', now()->subHours(1))->count();
        if ($failed_login_attempts >= 5) {
            return response()->json(['errors' => ['title' => 'Too Many Requests', 'body' => 'Try Again After A While']], 429);
        }
        $rules = [
            'password' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
        ];
        $request->validate($rules, $messages);
        $check = Hash::check($request->input('password'), auth()->user()->password);
        if (!$check) {
            FailedLoginAttempt::create([
                'user_id' => auth()->user()->id,
                'type' => 'password',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);
            return response()->json(['errors' => ['password' => ['Wrong Password']]], 422);
        }
        session()->forget('lockscreen');
        return response()->json(['success' => true], 200);
    }

}