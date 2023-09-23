<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RegistrationSuccess;
use App\Mail\VerifyEmail;
use App\Models\Guest;
use App\Models\User;
use App\Models\LoginInfo;
use App\Models\PersonalAccessToken;

class UserController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Login Page View
    // ---- ------ -- --- ----- ---- ----

    public function login(Request $request)
    {
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
        } else {
            $check = Hash::check($request->input('password'), $credentials->password);
            if (!$check) {
                return response()->json(['errors' => ['password' => ['Wrong Password']]], 422);
            }
        }
        LoginInfo::where(['user_id' => $credentials->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        Auth::login($credentials);
        LoginInfo::create([
            'user_id' => Auth::user()->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 1,
            'created_at' => now()
        ]);
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
            'token' => Str::random(60),
            'status' => 1,
            'created_at' => now()
        ]);
        $emailData = [
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
        $verifiable = PersonalAccessToken::where(['token' => $token, 'status' => 1])->first();
        if (!empty($verifiable)) {
            PersonalAccessToken::where(['token' => $token])->update([
                'status' => 0,
                'updated_at' => now()
            ]);
            User::where(['id' => $verifiable->user_id])->update([
                'status' => 1,
                'email_verified_at' => now()
            ]);
            $user = User::find($verifiable->user_id);
            Mail::to($user->email)->send(new RegistrationSuccess(['name' => $user->name]));
            Auth::login($user);
            LoginInfo::create([
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 1,
                'created_at' => now()
            ]);
            return redirect()->route('index');
        } else {
            abort(404);
        }
    }

    // ---- ------ -- --- -------- ----- ---- ----
    // This Method Is For Password Reset Page View
    // ---- ------ -- --- -------- ----- ---- ----

    public function reset()
    {
        return view('users.password-reset');
    }

    // ---- ------ -- --- ------ --- -------- ---- ----
    // This Method Is For Create New Password Page View
    // ---- ------ -- --- ------ --- -------- ---- ----

    public function token()
    {
        return view('users.token');
    }

    // ---- ------ -- --- ---- ------ ---- ----
    // This Method Is For Lock Screen Page View
    // ---- ------ -- --- ---- ------ ---- ----

    public function lockscreen()
    {
        return view('users.lockscreen');
    }

    // ---- ------ -- --- --- ------ -------------- ---- ----
    // This Method Is For Two Factor Authentication Page View
    // ---- ------ -- --- --- ------ -------------- ---- ----

    public function twoFactor()
    {
        return view('users.two-factor');
    }

    // ---- ------ -- --- ---- ----- -------------- ---- ----
    // This Method Is For Lost Email Authentication Page View
    // ---- ------ -- --- ---- ----- -------------- ---- ----

    public function lostEmail()
    {
        return view('users.lost-email');
    }

}