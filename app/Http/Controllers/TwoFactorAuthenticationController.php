<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwoFactorAuthentication\DisableService;
use App\Services\TwoFactorAuthentication\EnableService;
use App\Services\TwoFactorAuthentication\LostEmailService;
use App\Services\TwoFactorAuthentication\LostEmailViewService;
use App\Services\TwoFactorAuthentication\RequestDisableService;
use App\Services\TwoFactorAuthentication\RequestEnableService;
use App\Services\TwoFactorAuthentication\TwoFactorService;
use App\Services\TwoFactorAuthentication\TwoFactorViewService;

class TwoFactorAuthenticationController extends Controller
{

    // ---- ------ -- --- ---------- -- ------ ---
    // This Method Is For Requesting To Enable 2FA
    // ---- ------ -- --- ---------- -- ------ ---

    public function requestEnableTwoFactor(Request $request, RequestEnableService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- -------- ---
    // This Method Is For Enabling 2FA
    // ---- ------ -- --- -------- ---

    public function enableTwoFactor(Request $request, string $token, EnableService $service)
    {
        return $service->handle($request, $token);
    }

    // ---- ------ -- --- ---------- -- ------- ---
    // This Method Is For Requesting To Disable 2FA
    // ---- ------ -- --- ---------- -- ------- ---

    public function requestDisableTwoFactor(Request $request, RequestDisableService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- --------- ---
    // This Method Is For Disabling 2FA
    // ---- ------ -- --- --------- ---

    public function disableTwoFactor(Request $request, string $token, DisableService $service)
    {
        return $service->handle($request, $token);
    }

    // ---- ------ -- --- --- ------ -------------- ---- ----
    // This Method Is For Two Factor Authentication Page View
    // ---- ------ -- --- --- ------ -------------- ---- ----

    public function twoFactor(TwoFactorViewService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- --- ------ -------------- -----
    // This Method Is For Two Factor Authentication Logic
    // ---- ------ -- --- --- ------ -------------- -----

    public function twoFactorAuth(Request $request, TwoFactorService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ---- ----- -------------- ---- ----
    // This Method Is For Lost Email Authentication Page View
    // ---- ------ -- --- ---- ----- -------------- ---- ----

    public function lostEmail(LostEmailViewService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ---- ----- -------------- -----
    // This Method Is For Lost Email Authentication Logic
    // ---- ------ -- --- ---- ----- -------------- -----

    public function lostEmailAuth(Request $request, LostEmailService $service)
    {
        return $service->handle($request);
    }

}