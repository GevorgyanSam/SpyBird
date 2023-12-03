<?php

namespace App\Services\User;

use App\Jobs\PasswordChangeJob;
use App\Models\LoginInfo;
use App\Models\Notification;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Models\UserDataHistory;
use Illuminate\Support\Facades\Cache;

class PasswordChangeService
{

    // --- ---- -------- --- ------ --- --------
    // The Main Function For Create New Password
    // --- ---- -------- --- ------ --- --------

    public function handle($request)
    {
        $this->validate($request);
        $user = $this->getTokenOwner($request);
        $this->updateUserPassword($user, $request);
        $this->createHistory($user);
        $this->createNotification($user);
        $this->logoutOtherDevices($user);
        $this->sendMail($user);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ----------- ---- ----
    // This Method Is For Validataing User Data
    // ---- ------ -- --- ----------- ---- ----

    protected function validate($request)
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
    }

    // ---- ------ -- --- ------- ----- -----
    // This Method Is For Getting Token Owner
    // ---- ------ -- --- ------- ----- -----

    protected function getTokenOwner($request)
    {
        return PersonalAccessToken::where('token', $request->input('token'))
            ->first()
            ->user;
    }

    // ---- ------ -- --- -------- ---- --------
    // This Method Is For Updating User Password
    // ---- ------ -- --- -------- ---- --------

    protected function updateUserPassword($user, $request)
    {
        User::find($user->id)->update([
            'password' => $request->input('password'),
            'updated_at' => now()
        ]);
    }

    // ---- ------ -- --- -------- ---- ---- -------
    // This Method Is For Creating User Data History
    // ---- ------ -- --- -------- ---- ---- -------

    protected function createHistory($user)
    {
        UserDataHistory::create([
            'user_id' => $user->id,
            'type' => 'password_change',
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Creating Notification
    // ---- ------ -- --- -------- ------------

    protected function createNotification($user)
    {
        Notification::create([
            "user_id" => $user->id,
            "sender_id" => $user->id,
            "type" => "password_change",
            "content" => "password updated successfully",
            "seen" => 0,
            "status" => 1,
            "created_at" => now()
        ]);
    }

    // ---- ------ -- --- ------- --- -- ----- -------
    // This Method Is For Logging Out Of Other Devices
    // ---- ------ -- --- ------- --- -- ----- -------

    protected function logoutOtherDevices($user)
    {
        LoginInfo::where(['user_id' => $user->id, 'status' => 1])->update([
            'status' => 0,
            'updated_at' => now()
        ]);
        $cacheName = "device_" . $user->id;
        Cache::forget($cacheName);
    }

    // ---- ------ -- --- ------- ------------ -----
    // This Method Is For Sending Confirmation Email
    // ---- ------ -- --- ------- ------------ -----

    protected function sendMail($user)
    {
        $jobData = (object) [
            'email' => $user->email,
            'name' => $user->name
        ];
        PasswordChangeJob::dispatch($jobData);
    }

}