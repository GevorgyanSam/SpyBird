<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\LoginInfo;
use App\Models\User;
use App\Models\UserDataHistory;

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

    // ---- ------ -- --- ------ -------
    // This Method Is For Update Profile
    // ---- ------ -- --- ------ -------

    public function updateProfile(Request $request)
    {
        $rules = [
            'avatar' => ['bail', 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10000'],
            'name' => ['bail', 'nullable']
        ];
        $messages = [];
        $request->validate($rules, $messages);
        if ($request->hasFile("avatar") && $request->file("avatar")->isValid()) {
            $fileName = Str::random(10) . "_" . now()->timestamp . "_" . auth()->user()->id . "." . $request->file("avatar")->getClientOriginalExtension();
            $path = $request->file("avatar")->storeAs("assets", $fileName);
            UserDataHistory::create([
                "user_id" => auth()->user()->id,
                "type" => "avatar_change",
                "from" => auth()->user()->avatar,
                "to" => $path,
                "created_at" => now()
            ]);
            User::where(["id" => auth()->user()->id, "status" => 1])->update([
                "avatar" => $path,
                "updated_at" => now()
            ]);
        }
        if ($request->name) {
            UserDataHistory::create([
                "user_id" => auth()->user()->id,
                "type" => "name_change",
                "from" => strtolower(auth()->user()->name),
                "to" => strtolower($request->name),
                "created_at" => now()
            ]);
            User::where(["id" => auth()->user()->id, "status" => 1])->update([
                "name" => strtolower($request->name),
                "updated_at" => now()
            ]);
        }
        if ($request->name || $request->file("avatar")) {
            return response()->json(["refresh" => true], 200);
        }
        return response()->json(["default" => true], 200);
    }

    // ---- ------ -- --- ------
    // This Method Is For Logout
    // ---- ------ -- --- ------

    public function logout(Request $request)
    {
        LoginInfo::where(['user_id' => Auth::user()->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return response()->json(['success' => true], 200);
    }

}