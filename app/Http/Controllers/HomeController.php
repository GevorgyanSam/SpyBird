<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginInfo;

class HomeController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Index Page View
    // ---- ------ -- --- ----- ---- ----

    public function index()
    {
        return view('pages.index');
    }

    // ---- ------ -- --- ---- ---- ----
    // This Method Is For Chat Page View
    // ---- ------ -- --- ---- ---- ----

    public function room(int $id)
    {
        return view('pages.room');
    }

    // ---- ------ -- --- ------
    // This Method Is For Logout
    // ---- ------ -- --- ------

    public function logout()
    {
        LoginInfo::where(['user_id' => Auth::user()->id])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('user.login');
    }

}