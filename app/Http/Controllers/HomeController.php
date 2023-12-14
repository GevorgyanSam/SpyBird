<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Block\GetBlockedRelationshipService;
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
        $user = User::where('id', $id)
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->count();
        if (!$user) {
            return response()->json(['error' => true], 404);
        }
        $response = (object) [];
        $friendshipStatusService = new GetFriendshipStatusService();
        $response->friend = $friendshipStatusService->handle($id);
        $blockedRelationshipService = new GetBlockedRelationshipService();
        $response->blocked = $blockedRelationshipService->handle($id);
        return response()->json($response, 200);
    }

}