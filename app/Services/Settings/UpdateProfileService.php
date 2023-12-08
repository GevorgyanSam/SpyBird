<?php

namespace App\Services\Settings;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserDataHistory;
use Illuminate\Support\Str;

class UpdateProfileService
{

    // --- ---- -------- --- -------- ---- -------
    // The Main Function For Updating User Profile
    // --- ---- -------- --- -------- ---- -------

    public function handle($request)
    {
        $this->validate($request);
        if ($this->sameName($request)) {
            return response()->json(["errors" => ["name" => ["You Are Using The Same Name"]]], 422);
        }
        if ($this->hasValidFile($request)) {
            $fileName = $this->generateFileName($request);
            $path = $this->storeAndGetPath($request, $fileName);
            $this->createUserDataHistoryForFile($path);
            $this->createNotificationForFile();
            $this->updateUserForFile($path);
        }
        if ($this->hasValidName($request)) {
            $this->createUserDataHistoryForName($request);
            $this->createNotificationForName();
            $this->updateUserForName($request);
        }
        if ($this->anyChange($request)) {
            $user = $this->getUser();
            $avatar = $this->pathToHtml($user);
            $data = $this->getFinalData($avatar, $user);
            return response()->json(["success" => true, "data" => $data], 200);
        }
        return response()->json(["default" => true], 200);
    }

    // ---- ------ -- --- ---------- ---- ----
    // This Method Is For Validating User Data
    // ---- ------ -- --- ---------- ---- ----

    private function validate($request)
    {
        $rules = [
            'avatar' => ['bail', 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5000'],
            'name' => ['bail', 'nullable']
        ];
        $messages = [
            "max" => ":attribute is too big"
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- -------- -------- ---- -- ----- ---- -- ---
    // This Method Is For Checking Provided Name Is Owner Name Or Not
    // ---- ------ -- --- -------- -------- ---- -- ----- ---- -- ---

    private function sameName($request)
    {
        return (strtolower($request->name) == strtolower(auth()->user()->name) ? true : false);
    }

    // ---- ------ -- --- -------- ---- -------- ---- -- --- --- ---- -- ----- -- ---
    // This Method Is For Checking User Provided File Or Not And File Is Valid Or Not
    // ---- ------ -- --- -------- ---- -------- ---- -- --- --- ---- -- ----- -- ---

    private function hasValidFile($request)
    {
        return ($request->hasFile("avatar") && $request->file("avatar")->isValid()) ? true : false;
    }

    // ---- ------ -- --- ---------- ------ ---- ----
    // This Method Is For Generating Unique File Name
    // ---- ------ -- --- ---------- ------ ---- ----

    private function generateFileName($request)
    {
        return Str::random(10) . "_" . now()->timestamp . "_" . auth()->user()->id . "." . $request->file("avatar")->getClientOriginalExtension();
    }

    // ---- ------ -- --- ------- ---- --- --------- ---- ----
    // This Method Is For Storing File And Returning File Path
    // ---- ------ -- --- ------- ---- --- --------- ---- ----

    private function storeAndGetPath($request, $fileName)
    {
        return $request->file("avatar")->storeAs("assets", $fileName);
    }

    // ---- ------ -- --- -------- ---- ---- -------
    // This Method Is For Creating User Data History
    // ---- ------ -- --- -------- ---- ---- -------

    private function createUserDataHistoryForFile($path)
    {
        UserDataHistory::create([
            "user_id" => auth()->user()->id,
            "type" => "avatar_change",
            "from" => auth()->user()->avatar,
            "to" => $path,
            "created_at" => now()
        ]);
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Creating Notification
    // ---- ------ -- --- -------- ------------

    private function createNotificationForFile()
    {
        Notification::create([
            "user_id" => auth()->user()->id,
            "sender_id" => auth()->user()->id,
            "type" => "avatar_change",
            "content" => "avatar updated successfully",
            "seen" => 0,
            "status" => 1,
            "created_at" => now()
        ]);
    }

    // ---- ------ -- --- -------- ----
    // This Method Is For Updating User
    // ---- ------ -- --- -------- ----

    private function updateUserForFile($path)
    {
        User::where("id", auth()->user()->id)
            ->where("status", 1)
            ->update([
                "avatar" => $path,
                "updated_at" => now()
            ]);
    }

    // ---- ------ -- --- -------- --- ----- -------- ----
    // This Method Is For Checking For Valid Provided Name
    // ---- ------ -- --- -------- --- ----- -------- ----

    private function hasValidName($request)
    {
        return $request->name ? true : false;
    }

    // ---- ------ -- --- -------- ---- ---- -------
    // This Method Is For Creating User Data History
    // ---- ------ -- --- -------- ---- ---- -------

    private function createUserDataHistoryForName($request)
    {
        UserDataHistory::create([
            "user_id" => auth()->user()->id,
            "type" => "name_change",
            "from" => strtolower(auth()->user()->name),
            "to" => strtolower($request->name),
            "created_at" => now()
        ]);
    }

    // ---- ------ -- --- -------- ------------
    // This Method Is For Creating Notification
    // ---- ------ -- --- -------- ------------

    private function createNotificationForName()
    {
        Notification::create([
            "user_id" => auth()->user()->id,
            "sender_id" => auth()->user()->id,
            "type" => "name_change",
            "content" => "name updated successfully",
            "seen" => 0,
            "status" => 1,
            "created_at" => now()
        ]);
    }

    // ---- ------ -- --- -------- ----
    // This Method Is For Updating User
    // ---- ------ -- --- -------- ----

    private function updateUserForName($request)
    {
        User::where("id", auth()->user()->id)
            ->where("status", 1)
            ->update([
                "name" => strtolower($request->name),
                "updated_at" => now()
            ]);
    }

    // ---- ------ -- -------- -- ----- -- --------- --- ------- -- ---
    // This Method Is Designed To Check If Something Has Changed Or Not
    // ---- ------ -- -------- -- ----- -- --------- --- ------- -- ---

    private function anyChange($request)
    {
        return ($request->name || $request->file("avatar")) ? true : false;
    }

    // ---- ------ -- --- ------- ----
    // This Method Is For Getting User
    // ---- ------ -- --- ------- ----

    private function getUser()
    {
        return User::find(auth()->user()->id);
        ;
    }

    // ---- ------ -------- -- --------- ----- ---- -- ---- ---------
    // This Method Designed To Transform Image Path To HTML Attribute
    // ---- ------ -------- -- --------- ----- ---- -- ---- ---------

    private function pathToHtml($user)
    {
        return $user->avatar ? "<img src='" . asset('storage/' . $user->avatar) . "'>" : $user->name[0];
    }

    // ---- ------ -- --- ------- ----- ---- --- --------
    // This Method Is For Getting Final Data For Response
    // ---- ------ -- --- ------- ----- ---- --- --------

    private function getFinalData($avatar, $user)
    {
        return (object) [
            'avatar' => $avatar,
            'name' => $user->name
        ];
    }

}