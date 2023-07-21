<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Login Page View
    // ---- ------ -- --- ----- ---- ----

    public function login ()
    {
        return view('users.login');
    }

    // ---- ------ -- --- ------------ ---- ----
    // This Method Is For Registration Page View
    // ---- ------ -- --- ------------ ---- ----

    public function register ()
    {
        return view('users.register');
    }

    // ---- ------ -- --- -------- ----- ---- ----
    // This Method Is For Password Reset Page View
    // ---- ------ -- --- -------- ----- ---- ----

    public function reset ()
    {
        return view('users.password-reset');
    }

    // ---- ------ -- --- ---- ------ ---- ----
    // This Method Is For Lock Screen Page View
    // ---- ------ -- --- ---- ------ ---- ----

    public function lockscreen ()
    {
        return view('users.lockscreen');
    }

}