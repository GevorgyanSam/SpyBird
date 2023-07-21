<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('guest')->controller(UserController::class)->name('user.')->group(function () {

    Route::get('/login', 'login')->name('login');
    Route::get('/register', 'register')->name('register');
    Route::get('/password-reset', 'reset')->name('reset');
    Route::get('/lockscreen', 'lockscreen')->name('lockscreen');
    Route::get('/password-reset/{token}', 'token')->name('token');

});

Route::redirect('/', '/login');