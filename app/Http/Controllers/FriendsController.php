<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Friends\ConfirmFriendRequestService;
use App\Services\Friends\RejectFriendRequestService;
use App\Services\Friends\RemoveFromFriendsService;
use App\Services\Friends\SendFriendRequestService;

class FriendsController extends Controller
{

    // ---- ------ -- --- ------- ------ ------- -- ----- ----
    // This Method Is For Sending Friend Request To Other User
    // ---- ------ -- --- ------- ------ ------- -- ----- ----

    public function sendFriendRequest(int $id, SendFriendRequestService $service)
    {
        return $service->handle($id);
    }

    // ---- ------ -- --- -------- ---- ------ ----
    // This Method Is For Removing From Friend List
    // ---- ------ -- --- -------- ---- ------ ----

    public function removeFromFriends(int $id, RemoveFromFriendsService $service)
    {
        return $service->handle($id);
    }

    // ---- ------ -- -------- -- ------- --- ------ -------
    // This Method Is Intended To Confirm The Friend Request
    // ---- ------ -- -------- -- ------- --- ------ -------

    public function confirmFriendRequest(Request $request, int $id, ConfirmFriendRequestService $service)
    {
        return $service->handle($request, $id);
    }

    // ---- ------ -- -------- -- ------ --- ------ -------
    // This Method Is Intended To Reject The Friend Request
    // ---- ------ -- -------- -- ------ --- ------ -------

    public function rejectFriendRequest(Request $request, int $id, RejectFriendRequestService $service)
    {
        return $service->handle($request, $id);
    }

}