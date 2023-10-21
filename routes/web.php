<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\HomeController;

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
        Route::get('/password-reset/{token}', 'token')->name('token');
        Route::post('/password-reset/{token}', 'tokenAuth')->name('token-auth');
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
        Route::post('/update-profile', 'updateProfile')->name('update-profile');
        Route::post('/password-reset', 'passwordReset')->name('password-reset');
        Route::post('/logout', 'logout')->name('logout');

    });

    Route::controller(UserController::class)->name('user.')->group(function () {

        Route::get('/lockscreen', 'lockscreen')->name('lockscreen');

    });

});

// ----- ------ --- --- --- -----
// These Routes Are For All Users
// ----- ------ --- --- --- -----

Route::controller(TermsController::class)->name('privacy.')->group(function () {

    Route::get('/privacy-policy', 'policy')->name('policy');
    Route::get('/terms-of-service', 'terms')->name('terms');

});