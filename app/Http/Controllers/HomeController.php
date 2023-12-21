<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Room;
use App\Models\RoomMemeber;
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
        $blocked = BlockedUser::where('user_id', $id)
            ->where('blocked_user_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        if ($blocked) {
            return response()->json(['error' => true], 404);
        }
        $response = (object) [];
        $friendshipStatusService = new GetFriendshipStatusService();
        $response->friend = $friendshipStatusService->handle($id);
        $blockedRelationshipService = new GetBlockedRelationshipService();
        $response->blocked = $blockedRelationshipService->handle($id);
        return response()->json($response, 200);
    }

    // ---- ------ -- --- ------- -------
    // This Method Is For Sending Message
    // ---- ------ -- --- ------- -------

    public function sendMessage(int $id)
    {
        $user = User::where('id', $id)
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->count();
        if (!$user) {
            return response()->json(['error' => true], 404);
        }
        $blocked = BlockedUser::where('user_id', $id)
            ->where('blocked_user_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        if ($blocked) {
            return response()->json(['error' => true], 404);
        }
        $room = Room::select('rooms.*')
            ->join('room_members as rm1', function ($join) {
                $join->on('rooms.id', '=', 'rm1.room_id')
                    ->where('rm1.user_id', auth()->user()->id);
            })
            ->join('room_members as rm2', function ($join) use ($id) {
                $join->on('rooms.id', '=', 'rm2.room_id')
                    ->where('rm2.user_id', $id);
            })
            ->where('rooms.status', 1)
            ->first();
        if (!$room) {
            $newRoom = Room::create([
                'status' => 1,
                'created_at' => now()
            ]);
            RoomMemeber::create([
                'user_id' => auth()->user()->id,
                'room_id' => $newRoom->id,
                'created_at' => now()
            ]);
            RoomMemeber::create([
                'user_id' => $id,
                'room_id' => $newRoom->id,
                'created_at' => now()
            ]);
            return response()->json(['room_id' => $newRoom->id], 200);
        }
        return response()->json(['room_id' => $room->id], 200);
    }

}