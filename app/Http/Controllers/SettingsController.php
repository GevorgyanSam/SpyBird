<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginInfo;
use App\Models\UserDataHistory;
use App\Models\User;
use App\Models\Notification;
use App\Models\PersonalAccessToken;
use App\Models\PersonalAccessTokenEvent;
use App\Mail\PasswordReset;
use App\Mail\AccountTermination;
use App\Mail\AccountTerminationConfirmation;
use Jenssegers\Agent\Agent;

class SettingsController extends Controller
{

    // ---- ------ -- --- ------- ----- ------- -------- --------- -----
    // This Method Is For Getting Login History (Device, Location, Date)
    // ---- ------ -- --- ------- ----- ------- -------- --------- -----

    public static function getLoginHistory(): array
    {
        $cacheName = "device_" . auth()->user()->id;
        if (Cache::has($cacheName)) {
            $devices = Cache::get($cacheName);
            return $devices;
        }
        $devices = [];
        $loginInfo = LoginInfo::where(['user_id' => auth()->user()->id])->orderByDesc('created_at')->limit(4)->get();
        foreach ($loginInfo as $item) {
            $agent = new Agent();
            $agent->setUserAgent($item->user_agent);
            $device = $agent->device();
            if (!$device || strtolower($device) == "webkit") {
                $device = $agent->platform();
            }
            $date = Carbon::parse($item->created_at)->format('d M H:i');
            $link = route("delete-device", ["id" => $item->id]);
            array_push($devices, [
                'link' => $link,
                'status' => $item->status,
                'platform' => $device,
                'location' => $item->location,
                'date' => $date,
                'deleted_at' => $item->deleted_at
            ]);
        }
        Cache::put($cacheName, $devices, now()->addHours(1));
        return $devices;
    }

    // ---- ------ -- --- ---------- -- ------ --------- ----
    // This Method Is For Requesting To Enable Invisible Mode
    // ---- ------ -- --- ---------- -- ------ --------- ----

    public function requestEnableInvisible()
    {
        if (auth()->user()->invisible) {
            return response()->json(['reload' => true], 200);
        }
        User::where(['id' => auth()->user()->id, 'status' => 1])->update([
            'invisible' => 1,
            'updated_at' => now()
        ]);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---------- -- ------- --------- ----
    // This Method Is For Requesting To Disable Invisible Mode
    // ---- ------ -- --- ---------- -- ------- --------- ----

    public function requestDisableInvisible()
    {
        if (!auth()->user()->invisible) {
            return response()->json(['reload' => true], 200);
        }
        User::where(['id' => auth()->user()->id, 'status' => 1])->update([
            'invisible' => 0,
            'updated_at' => now()
        ]);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---------- -- ---- --------
    // This Method Is For Requesting To Show Activity
    // ---- ------ -- --- ---------- -- ---- --------

    public function requestShowActivity()
    {
        if (auth()->user()->activity) {
            return response()->json(['reload' => true], 200);
        }
        User::where(['id' => auth()->user()->id, 'status' => 1])->update([
            'activity' => 1,
            'updated_at' => now()
        ]);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---------- -- ---- --------
    // This Method Is For Requesting To Hide Activity
    // ---- ------ -- --- ---------- -- ---- --------

    public function requestHideActivity()
    {
        if (!auth()->user()->activity) {
            return response()->json(['reload' => true], 200);
        }
        User::where(['id' => auth()->user()->id, 'status' => 1])->update([
            'activity' => 0,
            'updated_at' => now()
        ]);
        return response()->json(['success' => true], 200);
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
            Notification::create([
                "user_id" => auth()->user()->id,
                "sender_id" => auth()->user()->id,
                "type" => "avatar_change",
                "content" => "avatar updated successfully",
                "status" => 1,
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
            Notification::create([
                "user_id" => auth()->user()->id,
                "sender_id" => auth()->user()->id,
                "type" => "name_change",
                "content" => "name updated successfully",
                "status" => 1,
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
        $emailData = (object) [
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        Mail::to(auth()->user()->email)->send(new PasswordReset($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- -------- ------ ---- --------
    // This Method Is For Deleting Device From Settings
    // ---- ------ -- --- -------- ------ ---- --------

    public function deleteDevice(int $id)
    {
        $loginInfo = LoginInfo::where(['id' => $id, 'user_id' => auth()->user()->id, 'status' => 0, 'deleted_at' => null])->get();
        if (!$loginInfo->count()) {
            return response()->json([], 404);
        }
        LoginInfo::where(['id' => $id])->update([
            'deleted_at' => now()
        ]);
        $cacheName = "device_" . auth()->user()->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- ----------- -----
    // This Method Is For Account Termination Email
    // ---- ------ -- --- ------- ----------- -----

    public function deleteAccount(Request $request)
    {
        $old_tokens = PersonalAccessToken::where(['user_id' => auth()->user()->id, 'type' => 'account_termination'])->where('expires_at', '>', now())->count();
        if ($old_tokens >= 1) {
            return response()->json(['error' => true], 429);
        }
        $token = PersonalAccessToken::create([
            'user_id' => auth()->user()->id,
            'type' => 'account_termination',
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
            'name' => auth()->user()->name,
            'token' => $token->token
        ];
        Mail::to(auth()->user()->email)->send(new AccountTermination($emailData));
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- -----------
    // This Method Is For Account Termination
    // ---- ------ -- --- ------- -----------

    public function accountTermination(Request $request, string $token)
    {
        $verifiable = PersonalAccessToken::where(['token' => $token, 'type' => 'account_termination', 'status' => 1])->first();
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
        $user = User::where(['id' => $verifiable->user_id, 'status' => 1])->first();
        if (empty($user)) {
            abort(404);
        }
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $cacheName = "device_" . $user->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        User::where(['id' => $user->id])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        UserDataHistory::create([
            'user_id' => $user->id,
            'type' => 'account_termination',
            'from' => 1,
            'to' => 0,
            'created_at' => now()
        ]);
        $emailData = (object) [
            'name' => $user->name
        ];
        Mail::to($user->email)->send(new AccountTerminationConfirmation($emailData));
        return redirect()->route('user.login');
    }

    // ---- ------ -- --- ---------- - ------ ----
    // This Method Is For Requesting A Screen Lock
    // ---- ------ -- --- ---------- - ------ ----

    public function requestLockscreen()
    {
        session()->put('lockscreen', true);
        return response()->json(['lockscreen' => true], 200);
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
        $cacheName = "device_" . auth()->user()->id;
        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
        }
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return response()->json(['success' => true], 200);
    }

}