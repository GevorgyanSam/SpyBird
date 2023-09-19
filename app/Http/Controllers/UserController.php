<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\PersonalAccessToken;

class UserController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Login Page View
    // ---- ------ -- --- ----- ---- ----

    public function login()
    {
        return view('users.login');
    }

    // ---- ------ -- --- ------------ ---- ----
    // This Method Is For Registration Page View
    // ---- ------ -- --- ------------ ---- ----

    public function register()
    {
        return view('users.register');
    }

    public function registerAuth(Request $request)
    {
        $rules = [
            'name' => ['bail', 'required'],
            'email' => ['bail', 'required', 'email:rfc,dns', 'unique:users'],
            'password' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
            'unique' => ':attribute already exists',
            'email' => [
                'rfc' => 'enter valid :attribute address',
                'dns' => 'enter valid :attribute address'
            ]
        ];
        $request->validate($rules, $messages);
        $newUser = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);
        $newToken = PersonalAccessToken::create([
            'user_id' => $newUser->id,
            'token' => Str::random(60)
        ]);
        $emailData = [
            'name' => $newUser->name,
            'token' => $newToken->token
        ];
        Mail::to($newUser->email)->send(new VerifyEmail($emailData));
        return redirect()->route('user.login');
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