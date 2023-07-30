<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrivacyController;

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

    });

});

// ----- ------ --- --- ----- --- ------- ------
// These Routes Are For Terms And Privacy Policy
// ----- ------ --- --- ----- --- ------- ------

Route::controller(PrivacyController::class)->name('privacy.')->group(function () {

    Route::get('/privacy-policy', 'policy')->name('policy');
    Route::get('/terms-of-service', 'terms')->name('terms');

});