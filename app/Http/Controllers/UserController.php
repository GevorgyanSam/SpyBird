<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Mail\PasswordChange;
use App\Mail\PasswordReset;
use App\Mail\RegistrationSuccess;
use App\Mail\VerifyEmail;
use App\Mail\AuthenticationCode;
use App\Models\Guest;
use App\Models\User;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Models\UserDataHistory;
use App\Models\FailedLoginAttempt;
use App\Models\TwoFactorAuthentication;

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

    public function loginAuth(Request $request)
    {
        $failed_login_attempts = FailedLoginAttempt::where([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ])->where('created_at', '>', now()->subHours(1))->count();
        if ($failed_login_attempts >= 5) {
            return response()->json(['errors' => ['title' => 'Too Many Requests', 'body' => 'Try Again After A While']], 429);
        }
        $rules = [
            'email' => ['bail', 'required', 'email:rfc,dns,filter'],
            'password' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'email' => [
                'enter valid :attribute address'
            ],
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
        ];
        $request->validate($rules, $messages);
        $credentials = User::where(['email' => $request->input('email'), 'status' => 1])->first();
        if (empty($credentials)) {
            return response()->json(['errors' => ['email' => ['Undefined Account']]], 422);
        }
        $check = Hash::check($request->input('password'), $credentials->password);
        if (!$check) {
            FailedLoginAttempt::create([
                'user_id' => $credentials->id,
                'type' => 'password',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);
            return response()->json(['errors' => ['password' => ['Wrong Password']]], 422);
        }
        if ($credentials->two_factor_authentication) {
            $code = rand(10000000, 99999999);
            TwoFactorAuthentication::create([
                'user_id' => $credentials->id,
                'code' => $code,
                'status' => 1,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(20)
            ]);
            $emailData = (object) [
                'name' => $credentials->name,
                'code' => $code
            ];
            Mail::to($credentials->email)->send(new AuthenticationCode($emailData));
            $data = (object) [
                'id' => $credentials->id,
                'name' => $credentials->name,
                'email' => $credentials->email,
                'avatar' => $credentials->avatar
            ];
            session()->put('credentials', $data);
            return response()->json(['two-factor' => true], 200);
        }
        LoginInfo::where(['user_id' => $credentials->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        Auth::login($credentials);
        $login_id = LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(3)
        ]);
        session()->put('login-id', $login_id->id);
        $cacheName = "device_" . Auth::user()->id;
        Cache::forget($cacheName);
        return response()->json(['success' => true], 200);
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

    public function registerAuth(Request $request)
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
        $newUser = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 0,
            'two_factor_authentication' => 0,
            'created_at' => now()
        ]);
        $newToken = PersonalAccessToken::create([
            'user_id' => $newUser->id,
            'type' => 'registration',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(1)
        ]);
        PersonalAccessTokenEvent::create([
            'token_id' => $newToken->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $emailData = (object) [
            'name' => $newUser->name,
            'token' => $newToken->token
        ];
        Mail::to($newUser->email)->send(new VerifyEmail($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- --------- ----- ----- ------------
    // This Method Is For Verifying Email After Registration
    // ---- ------ -- --- --------- ----- ----- ------------

    public function verifyEmail(string $token, Request $request)
    {
        $verifiable = PersonalAccessToken::where(['token' => $token, 'type' => 'registration', 'status' => 1])->first();
        if (empty($verifiable)) {
            abort(404);
        }
        if ($verifiable->expires_at <= now()) {
            abort(404);
        }
        PersonalAccessToken::where(['token' => $token])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $tokenId = PersonalAccessToken::where('token', $token)->value('id');
        PersonalAccessTokenEvent::create([
            'token_id' => $tokenId,
            'type' => 'usage',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $user = User::find($verifiable->user_id);
        if (!empty($user->email_verified_at)) {
            abort(404);
        }
        User::where(['id' => $verifiable->user_id])->update([
            'status' => 1,
            'email_verified_at' => now()
        ]);
        $user = User::find($verifiable->user_id);
        $emailData = (object) ['name' => $user->name];
        Mail::to($user->email)->send(new RegistrationSuccess($emailData));
        Auth::login($user);
        $login_id = LoginInfo::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(3)
        ]);
        session()->put('login-id', $login_id->id);
        return redirect()->route('index');
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

    public function resetAuth(Request $request)
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
        $user = User::where('email', $request->input('email'))->where('status', 1)->orWhereNull('email_verified_at')->first();
        if (empty($user)) {
            return response()->json(['errors' => ['email' => ['Undefined Account']]], 422);
        }
        $old_tokens = PersonalAccessToken::where(['user_id' => $user->id, 'type' => 'password_reset'])->where('expires_at', '>', now())->count();
        if ($old_tokens >= 2) {
            return response()->json(['success' => true], 200);
        }
        $token = PersonalAccessToken::create([
            'user_id' => $user->id,
            'type' => 'password_reset',
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now(),
            'expires_at' => now()->addHours(1)
        ]);
        PersonalAccessTokenEvent::create([
            'token_id' => $token->id,
            'type' => 'request',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $emailData = (object) [
            'name' => $user->name,
            'token' => $token->token
        ];
        Mail::to($user->email)->send(new PasswordReset($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------ --- -------- ---- ----
    // This Method Is For Create New Password Page View
    // ---- ------ -- --- ------ --- -------- ---- ----

    public function token(string $token, Request $request)
    {
        $verifiable = PersonalAccessToken::where(['token' => $token, 'type' => 'password_reset', 'status' => 1])->first();
        if (empty($verifiable)) {
            abort(404);
        }
        if ($verifiable->expires_at <= now()) {
            abort(404);
        }
        PersonalAccessToken::where(['token' => $token])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $tokenId = PersonalAccessToken::where('token', $token)->value('id');
        PersonalAccessTokenEvent::create([
            'token_id' => $tokenId,
            'type' => 'usage',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        $user = User::where(['id' => $verifiable->user_id, 'status' => 1])->orWhereNull('email_verified_at')->first();
        if (empty($user)) {
            abort(404);
        }
        if (empty($user->email_verified_at)) {
            User::where('id', $user->id)->update([
                'status' => 1,
                'email_verified_at' => now()
            ]);
        }
        return view('users.token', ['user' => $user, 'token' => $token]);
    }

    // ---- ------ -- --- ------ --- -------- -----
    // This Method Is For Create New Password Logic
    // ---- ------ -- --- ------ --- -------- -----

    public function tokenAuth(Request $request)
    {
        $rules = [
            'password' => ['bail', 'required', 'min:8', 'confirmed'],
            'password_confirmation' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
            'confirmed' => ':attribute does not match'
        ];
        $request->validate($rules, $messages);
        $user = PersonalAccessToken::where('token', $request->input('token'))->first()->user;
        User::find($user->id)->update([
            'password' => $request->input('password'),
            'updated_at' => now()
        ]);
        UserDataHistory::create([
            'user_id' => $user->id,
            'type' => 'password_change',
            'created_at' => now()
        ]);
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $cacheName = "device_" . $user->id;
        Cache::forget($cacheName);
        $emailData = (object) [
            'name' => $user->name
        ];
        Mail::to($user->email)->send(new PasswordChange($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---- ------ ---- ----
    // This Method Is For Lock Screen Page View
    // ---- ------ -- --- ---- ------ ---- ----

    public function lockscreen()
    {
        return view('users.lockscreen');
    }

}