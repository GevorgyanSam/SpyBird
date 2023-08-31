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
        Route::get('/password-reset', 'reset')->name('reset');
        Route::get('/lockscreen', 'lockscreen')->name('lockscreen');
        Route::get('/password-reset/{token}', 'token')->name('token');
        Route::get('/two-factor-authentication', 'twoFactor')->name('two-factor');
        Route::get('/lost-email-authentication', 'lostEmail')->name('lost-email');

    });

    Route::controller(HomeController::class)->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/room/{id}', 'room')->name('room');

    });

});

// ----- ------ --- --- ----- --- ------- ------
// These Routes Are For Terms And Privacy Policy
// ----- ------ --- --- ----- --- ------- ------

Route::controller(PrivacyController::class)->name('privacy.')->group(function () {

    Route::get('/privacy-policy', 'policy')->name('policy');
    Route::get('/terms-of-service', 'terms')->name('terms');

});