<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('guest')->controller(UserController::class)->name('user.')->group(function () {

    Route::get('/login', 'login')->name('login');

});

Route::redirect('/', '/login');