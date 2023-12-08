<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Settings\AccountTerminationService;
use App\Services\Settings\DeleteAccountService;
use App\Services\Settings\DeleteDeviceService;
use App\Services\Settings\LogoutService;
use App\Services\Settings\PasswordResetService;
use App\Services\Settings\RequestDisableInvisibleService;
use App\Services\Settings\RequestEnableInvisibleService;
use App\Services\Settings\RequestHideActivityService;
use App\Services\Settings\RequestShowActivityService;
use App\Services\Settings\UpdateProfileService;

class SettingsController extends Controller
{

    // ---- ------ -- --- ---------- -- ------ --------- ----
    // This Method Is For Requesting To Enable Invisible Mode
    // ---- ------ -- --- ---------- -- ------ --------- ----

    public function requestEnableInvisible(RequestEnableInvisibleService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ---------- -- ------- --------- ----
    // This Method Is For Requesting To Disable Invisible Mode
    // ---- ------ -- --- ---------- -- ------- --------- ----

    public function requestDisableInvisible(RequestDisableInvisibleService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ---------- -- ---- --------
    // This Method Is For Requesting To Show Activity
    // ---- ------ -- --- ---------- -- ---- --------

    public function requestShowActivity(RequestShowActivityService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ---------- -- ---- --------
    // This Method Is For Requesting To Hide Activity
    // ---- ------ -- --- ---------- -- ---- --------

    public function requestHideActivity(RequestHideActivityService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ------ -------
    // This Method Is For Update Profile
    // ---- ------ -- --- ------ -------

    public function updateProfile(Request $request, UpdateProfileService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- -------- -----
    // This Method Is For Password Reset
    // ---- ------ -- --- -------- -----

    public function passwordReset(Request $request, PasswordResetService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- -------- ------ ---- --------
    // This Method Is For Deleting Device From Settings
    // ---- ------ -- --- -------- ------ ---- --------

    public function deleteDevice(int $id, DeleteDeviceService $service)
    {
        return $service->handle($id);
    }

    // ---- ------ -- --- ------- ----------- -----
    // This Method Is For Account Termination Email
    // ---- ------ -- --- ------- ----------- -----

    public function deleteAccount(Request $request, DeleteAccountService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ------- -----------
    // This Method Is For Account Termination
    // ---- ------ -- --- ------- -----------

    public function accountTermination(Request $request, string $token, AccountTerminationService $service)
    {
        return $service->handle($request, $token);
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

    public function logout(LogoutService $service)
    {
        return $service->handle();
    }

}