<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\HomeController;

// ----- ------ --- --- ----- -----
// These Routes Are For Guest Users
// ----- ------ --- --- ----- -----

Route::middleware('guest')->group(function () {

    Route::controller(UserController::class)->name('user.')->group(function () {

        Route::get('/login', 'login')->name('login');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'registerAuth')->name('register-auth');
        Route::get('/register/verify-email/{token}', 'verifyEmail')->name('verify-email');
        Route::get('/password-reset', 'reset')->name('reset');
        Route::get('/password-reset/{token}', 'token')->name('token');
        Route::get('/two-factor-authentication', 'twoFactor')->name('two-factor');
        Route::get('/lost-email-authentication', 'lostEmail')->name('lost-email');

    });

});

// ----- ------ --- --- ------------- -----
// These Routes Are For Authenticated Users
// ----- ------ --- --- ------------- -----

Route::middleware('auth')->group(function () {

    Route::controller(HomeController::class)->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/room/{id}', 'room')->name('room');

    });

    Route::controller(UserController::class)->name('user.')->group(function () {

        Route::get('/lockscreen', 'lockscreen')->name('lockscreen');

    });

});

// ----- ------ --- --- --- -----
// These Routes Are For All Users
// ----- ------ --- --- --- -----

Route::controller(PrivacyController::class)->name('privacy.')->group(function () {

    Route::get('/privacy-policy', 'policy')->name('policy');
    Route::get('/terms-of-service', 'terms')->name('terms');

});