<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TwoFactorAuthenticationController;

// ----- ------ --- --- ----- -----
// These Routes Are For Guest Users
// ----- ------ --- --- ----- -----

Route::middleware('guest')->group(function () {

    Route::controller(UserController::class)->name('user.')->group(function () {

        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'loginAuth')->name('login-auth');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'registerAuth')->name('register-auth');
        Route::get('/register/verify-email/{token}', 'verifyEmail')->name('verify-email');
        Route::get('/password-reset', 'reset')->name('reset');
        Route::post('/password-reset', 'resetAuth')->name('reset-auth');

    });

    Route::controller(TwoFactorAuthenticationController::class)->group(function () {

        Route::get('/two-factor-authentication', 'twoFactor')->name('two-factor');
        Route::post('/two-factor-authentication', 'twoFactorAuth')->name('two-factor-auth');
        Route::get('/lost-email-authentication', 'lostEmail')->name('lost-email');
        Route::post('/lost-email-authentication', 'lostEmailAuth')->name('lost-email-auth');

    });

});

// ----- ------ --- --- ------------- -----
// These Routes Are For Authenticated Users
// ----- ------ --- --- ------------- -----

Route::middleware(['auth', 'lockscreen'])->group(function () {

    Route::controller(HomeController::class)->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/room/{id}', 'room')->name('room');
        Route::post('/update-profile', 'updateProfile')->name('update-profile');
        Route::post('/reset-password', 'passwordReset')->name('password-reset');
        Route::post('/delete-device/{id}', 'deleteDevice')->name('delete-device');
        Route::post('/delete-account', 'deleteAccount')->name('delete-account');
        Route::post('/check-authentication', 'checkAuthentication')->name('check-authentication')->withoutMiddleware('lockscreen');
        Route::post('/request-lockscreen', 'requestLockscreen')->name('request-lockscreen');
        Route::post('/logout', 'logout')->name('logout')->withoutMiddleware('lockscreen');;

    });

    Route::controller(TwoFactorAuthenticationController::class)->group(function () {

        Route::post('/request-enable-two-factor-authentication', 'requestEnableTwoFactor')->name('request-enable-two-factor');
        Route::post('/request-disable-two-factor-authentication', 'requestDisableTwoFactor')->name('request-disable-two-factor');

    });

    Route::withoutMiddleware('lockscreen')->controller(UserController::class)->name('user.')->group(function () {

        Route::get('/lockscreen', 'lockscreen')->name('lockscreen');
        Route::post('/lockscreen', 'lockscreenAuth')->name('lockscreen-auth');

    });

});

// ----- ------ --- --- ------ --- ------------- -----
// These Routes Are For Guests And Authenticated Users
// ----- ------ --- --- ------ --- ------------- -----

Route::controller(TermsController::class)->name('privacy.')->group(function () {

    Route::get('/privacy-policy', 'policy')->name('policy');
    Route::get('/terms-of-service', 'terms')->name('terms');

});

Route::controller(HomeController::class)->group(function () {

    Route::get('/account-termination/{token}', 'accountTermination')->name('account-termination');

});

Route::controller(TwoFactorAuthenticationController::class)->group(function () {

    Route::get('/enable-two-factor-authentication/{token}', 'enableTwoFactor')->name('enable-two-factor');
    Route::get('/disable-two-factor-authentication/{token}', 'disableTwoFactor')->name('disable-two-factor');

});

Route::controller(UserController::class)->name('user.')->group(function () {

    Route::get('/password-reset/{token}', 'token')->name('token');
    Route::post('/password-reset/{token}', 'tokenAuth')->name('token-auth');

});