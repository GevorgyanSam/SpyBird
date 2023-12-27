<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Message;
use App\Models\Room;
use App\Models\RoomMemeber;
use App\Models\User;
use App\Services\Block\GetBlockedRelationshipService;
use App\Services\Settings\GetLoginHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RoomController extends Controller
{

    // ---- ------ -- --- ---- ---- ----
    // This Method Is For Chat Page View
    // ---- ------ -- --- ---- ---- ----

    public function room(int $id, GetLoginHistoryService $getLoginHistoryService)
    {
        $room = Room::join('room_members', 'rooms.id', '=', 'room_members.room_id')
            ->where('rooms.id', $id)
            ->where('rooms.status', 1)
            ->where('room_members.user_id', auth()->user()->id)
            ->count();
        if (!$room) {
            return redirect()->route('index');
        }
        $member = RoomMemeber::select('user_id')
            ->where('room_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->get();
        if (!$member->count()) {
            return redirect()->route('index');
        }
        $user_id = $member[0]->user_id;
        $user = User::with('latestLoginInfo')
            ->where('id', $user_id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->first();
        if (!$user) {
            return redirect()->route('index');
        }
        $blocked = BlockedUser::where('user_id', $user->id)
            ->where('blocked_user_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        if ($blocked) {
            return redirect()->route('index');
        }
        Message::where('status', 1)
            ->where('seen', 0)
            ->where('room_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->update([
                'seen' => 1,
                'updated_at' => now()
            ]);
        $client = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'active' => $user->activity && $user->latestLoginInfo->status ? 'active' : null,
            'status' => $user->activity ? ($user->latestLoginInfo->status ? 'online' : Carbon::parse($user->latestLoginInfo->updated_at)->format('d M H:i')) : 'hidden status',
        ];
        $devices = $getLoginHistoryService->handle();
        return view('pages.room', ['devices' => $devices, 'client' => $client, 'room' => $id]);
    }

    // ---- ------ -- --- ------- ---- -------- ----
    // This Method Is For Getting Room Dropdown Data
    // ---- ------ -- --- ------- ---- -------- ----

    public function getRoomDropdownData(int $id)
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
        $blockedRelationshipService = new GetBlockedRelationshipService();
        $response->blocked = $blockedRelationshipService->handle($id);
        return response()->json($response, 200);
    }

    // ---- ------ -- -------- -- ------ ----
    // This Method Is Designed To Delete Chat
    // ---- ------ -- -------- -- ------ ----

    public function deleteChat(int $id)
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
        $room = RoomMemeber::from('room_members as rm1')
            ->join('room_members as rm2', 'rm1.room_id', '=', 'rm2.room_id')
            ->where('rm1.user_id', '=', auth()->user()->id)
            ->where('rm2.user_id', '=', $id)
            ->select('rm1.room_id')
            ->first();
        $count = Room::where('id', $room->room_id)
            ->where('status', 1)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if (!$count) {
            return response()->json(['error' => true], 404);
        }
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- --------
    // This Method Is For Getting Messages
    // ---- ------ -- --- ------- --------

    public function getMessages(int $id)
    {
        $messages = Message::where('room_id', $id)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
        if (!$messages->count()) {
            return response()->json(['empty' => true], 200);
        }
        return response()->json(['messages' => $messages], 200);
    }

    // ---- ------ -- --- ------- --- --------
    // This Method Is For Getting New Messages
    // ---- ------ -- --- ------- --- --------

    public function getNewMessages(int $id)
    {
        $room = Room::join('room_members', 'rooms.id', '=', 'room_members.room_id')
            ->where('rooms.id', $id)
            ->where('rooms.status', 1)
            ->where('room_members.user_id', auth()->user()->id)
            ->count();
        if (!$room) {
            return response()->json(['redirect' => true], 403);
        }
        $member = RoomMemeber::select('user_id')
            ->where('room_id', $id)
            ->where('user_id', '!=', auth()->user()->id)
            ->get();
        if (!$member->count()) {
            return response()->json(['redirect' => true], 404);
        }
        $user_id = $member[0]->user_id;
        $user = User::with('latestLoginInfo')
            ->where('id', $user_id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->first();
        if (!$user) {
            return response()->json(['redirect' => true], 404);
        }
        $blocked = BlockedUser::where('user_id', $user->id)
            ->where('blocked_user_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        if ($blocked) {
            return response()->json(['redirect' => true], 404);
        }
        $client = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'active' => $user->activity && $user->latestLoginInfo->status ? 'active' : null,
            'status' => $user->activity ? ($user->latestLoginInfo->status ? 'online' : Carbon::parse($user->latestLoginInfo->updated_at)->format('d M H:i')) : 'hidden status',
        ];
        $messages = Message::where('room_id', $id)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
        if (!$messages->count()) {
            return response()->json(['client' => $client, 'empty' => true], 200);
        }
        return response()->json(['client' => $client, 'messages' => $messages], 200);
    }

}