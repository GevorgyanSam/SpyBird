<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use App\Models\LoginInfo;
use App\Models\User;
use App\Models\UserDataHistory;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;

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
            'avatar' => ['bail', 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5000'],
            'name' => ['bail', 'nullable']
        ];
        $messages = [
            "max" => ":attribute is too big"
        ];
        $request->validate($rules, $messages);
        if (strtolower($request->name) == strtolower(auth()->user()->name)) {
            return response()->json(["errors" => ["name" => ["You Are Using The Same Name"]]], 422);
        }
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

    // ---- ------ -- --- -------- -----
    // This Method Is For Password Reset
    // ---- ------ -- --- -------- -----

    public function passwordReset(Request $request)
    {
        $old_tokens = PersonalAccessToken::where(['user_id' => auth()->user()->id, 'type' => 'password_reset'])->where('expires_at', '>', now())->count();
        if ($old_tokens >= 2) {
            return response()->json(['success' => true], 200);
        }
        $token = PersonalAccessToken::create([
            'user_id' => auth()->user()->id,
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
        $emailData = [
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        Mail::to(auth()->user()->email)->send(new PasswordReset($emailData));
        return response()->json(['success' => true], 200);
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