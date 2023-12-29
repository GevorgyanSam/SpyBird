<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\RoomController;
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
        Route::post('/check-authentication', 'checkAuthentication')->name('check-authentication')->withoutMiddleware('lockscreen');
        Route::post('/get-aside-dropdown-data/{id}', 'getAsideDropdownData')->name('get-aside-dropdown-data');
        Route::post('/send-message/{id}', 'sendMessage')->name('send-message');
        Route::post('/get-chats', 'getChats')->name('get-chats');
        Route::post('/search-chats', 'searchChats')->name('search-chats');

    });

    Route::controller(RoomController::class)->group(function () {

        Route::get('/room/{id}', 'room')->name('room');
        Route::post('/get-room-dropdown-data/{id}', 'getRoomDropdownData')->name('get-room-dropdown-data');
        Route::post('/delete-chat/{id}', 'deleteChat')->name('delete-chat');
        Route::post('/get-messages/{id}', 'getMessages')->name('get-messages');
        Route::post('/get-new-messages/{id}', 'getNewMessages')->name('get-new-messages');
        Route::post('/set-seen-message/{id}', 'setSeenMessage')->name('set-seen-message');
        Route::post('/send-letter/{id}', 'sendLetter')->name('send-letter');
        Route::post('/delete-message/{id}', 'deleteMessage')->name('delete-message');
        Route::post('/like-message/{id}', 'likeMessage')->name('like-message');

    });

    Route::controller(SettingsController::class)->group(function () {

        Route::post('/request-enable-invisible-mode', 'requestEnableInvisible')->name('request-enable-invisible-mode');
        Route::post('/request-disable-invisible-mode', 'requestDisableInvisible')->name('request-disable-invisible-mode');
        Route::post('/request-enable-spy-mode', 'requestEnableSpy')->name('request-enable-spy-mode');
        Route::post('/request-disable-spy-mode', 'requestDisableSpy')->name('request-disable-spy-mode');
        Route::post('/request-show-activity', 'requestShowActivity')->name('request-show-activity');
        Route::post('/request-hide-activity', 'requestHideActivity')->name('request-hide-activity');
        Route::post('/update-profile', 'updateProfile')->name('update-profile');
        Route::post('/reset-password', 'passwordReset')->name('password-reset');
        Route::post('/delete-device/{id}', 'deleteDevice')->name('delete-device');
        Route::post('/delete-account', 'deleteAccount')->name('delete-account');
        Route::post('/request-lockscreen', 'requestLockscreen')->name('request-lockscreen');
        Route::post('/logout', 'logout')->name('logout')->withoutMiddleware('lockscreen');

    });

    Route::controller(SearchController::class)->group(function () {

        Route::post('/get-suggested-contacts', 'getSuggestedContacts')->name('get-suggested-contacts');
        Route::post('/get-nearby-contacts', 'getNearbyContacts')->name('get-nearby-contacts');
        Route::post('/search-contacts', 'searchContacts')->name('search-contacts');

    });

    Route::controller(NotificationsController::class)->group(function () {

        Route::post('/get-notifications', 'getNotifications')->name('get-notifications');
        Route::post('/clear-notifications', 'clearNotifications')->name('clear-notifications');
        Route::post('/delete-notification/{id}', 'deleteNotification')->name('delete-notification');
        Route::post('/check-new-notifications', 'checkNewNotifications')->name('check-new-notifications');
        Route::post('/set-seen-notifications', 'setSeenNotifications')->name('set-seen-notifications');
        Route::post('/get-new-notifications', 'getNewNotifications')->name('get-new-notifications');

    });

    Route::controller(FriendsController::class)->group(function () {

        Route::post('/send-friend-request/{id}', 'sendFriendRequest')->name('send-friend-request');
        Route::post('/remove-from-friends/{id}', 'removeFromFriends')->name('remove-from-friends');
        Route::post('/confirm-friend-request/{id}', 'confirmFriendRequest')->name('confirm-friend-request');
        Route::post('/reject-friend-request/{id}', 'rejectFriendRequest')->name('reject-friend-request');
        Route::post('/get-friends', 'getFriends')->name('get-friends');
        Route::post('/search-friends', 'searchFriends')->name('search-friends');

    });

    Route::controller(BlockController::class)->group(function () {

        Route::post('/unblock-user/{id}', 'unblockUser')->name('unblock-user');
        Route::post('/block-user/{id}', 'blockUser')->name('block-user');

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

Route::controller(SettingsController::class)->group(function () {

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