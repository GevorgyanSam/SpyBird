<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Friends\GetFriendshipStatusService;
use App\Services\Home\CheckAuthenticationService;
use App\Services\Settings\GetLoginHistoryService;

class HomeController extends Controller
{

    // ---- ------ -- --- ----- ---- ----
    // This Method Is For Index Page View
    // ---- ------ -- --- ----- ---- ----

    public function index(GetLoginHistoryService $getLoginHistoryService)
    {
        $devices = $getLoginHistoryService->handle();
        return view('pages.index', ['devices' => $devices]);
    }

    // ---- ------ -- --- ---- ---- ----
    // This Method Is For Chat Page View
    // ---- ------ -- --- ---- ---- ----

    public function room(int $id, GetLoginHistoryService $getLoginHistoryService)
    {
        $devices = $getLoginHistoryService->handle();
        return view('pages.room', ['devices' => $devices]);
    }

    // ---- ------ -- --- -------- --------------
    // This Method Is For Checking Authentication
    // ---- ------ -- --- -------- --------------

    public function checkAuthentication(Request $request, CheckAuthenticationService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- ------- ------------ ------- -----
    // This Method Is For Getting Relationship Between Users
    // ---- ------ -- --- ------- ------------ ------- -----

    public function getRelationship(int $id)
    {
        $response = (object) [];
        $service = new GetFriendshipStatusService();
        $response->friend = $service->handle($id);
        return response()->json($response, 200);
    }

}