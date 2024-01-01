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
        $room = Room::select('rooms.user_id', 'rooms.spy')
            ->join('room_members', 'rooms.id', '=', 'room_members.room_id')
            ->where('rooms.id', $id)
            ->where('rooms.status', 1)
            ->where('room_members.user_id', auth()->user()->id)
            ->first();
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
        $client = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'active' => $user->activity && $user->latestLoginInfo->status ? 'active' : null,
            'status' => $user->activity ? ($user->latestLoginInfo->status ? 'online' : Carbon::parse($user->latestLoginInfo->updated_at)->format('d M H:i')) : 'hidden status',
        ];
        $area = (object) [
            'owner' => $room->user_id,
            'spy' => $room->spy
        ];
        $devices = $getLoginHistoryService->handle();
        return view('pages.room', ['devices' => $devices, 'client' => $client, 'room' => $id, 'area' => $area]);
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

    public function deleteChat(Request $request, int $id)
    {
        $spy = $request->input('spy');
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
            ->join('rooms', 'rm1.room_id', '=', 'rooms.id')
            ->where('rm1.user_id', '=', auth()->user()->id)
            ->where('rm2.user_id', '=', $id)
            ->where('rooms.status', '=', 1)
            ->where('rooms.spy', '=', $spy)
            ->select('rm1.room_id')
            ->first();
        $count = Room::where('id', $room->room_id)
            ->where('spy', $spy)
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
        $room = Room::select('rooms.user_id', 'rooms.spy')
            ->join('room_members', 'rooms.id', '=', 'room_members.room_id')
            ->where('rooms.id', $id)
            ->where('rooms.status', 1)
            ->where('room_members.user_id', auth()->user()->id)
            ->first();
        if (!$room) {
            return response()->json(['redirect' => true], 403);
        }
        if (auth()->user()->spy) {
            if (($room->spy && $room->user_id != auth()->user()->id) || !$room->spy) {
                return response()->json(['redirect' => true], 403);
            }
        } else {
            if ($room->spy && $room->user_id == auth()->user()->id) {
                return response()->json(['redirect' => true], 403);
            }
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
        if ($room->spy && $room->user_id != auth()->user()->id) {
            if (!$messages->count()) {
                return response()->json(['empty' => true], 200);
            }
            return response()->json(['messages' => $messages], 200);
        }
        if (!$messages->count()) {
            return response()->json(['client' => $client, 'empty' => true], 200);
        }
        return response()->json(['client' => $client, 'messages' => $messages], 200);
    }

    // ---- ------ -- --- ------- ------- -- ----
    // This Method Is For Marking Message As Seen
    // ---- ------ -- --- ------- ------- -- ----

    public function setSeenMessage(int $id)
    {
        Message::where('id', $id)
            ->where('status', 1)
            ->where('seen', 0)
            ->where('user_id', '!=', auth()->user()->id)
            ->update([
                'seen' => 1,
                'updated_at' => now()
            ]);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ------- -------
    // This Method Is For Sending Message
    // ---- ------ -- --- ------- -------

    public function sendLetter(Request $request, int $id)
    {
        $rules = [
            'letter' => ['bail', 'required']
        ];
        $messages = [
            'required' => 'enter :attribute',
        ];
        $request->validate($rules, $messages);
        $room = Room::join('room_members', 'rooms.id', '=', 'room_members.room_id')
            ->where('rooms.id', $id)
            ->where('rooms.status', 1)
            ->where('room_members.user_id', auth()->user()->id)
            ->count();
        if (!$room) {
            return response()->json(['redirect' => true], 403);
        }
        $letter = $request->input('letter');
        Message::create([
            'user_id' => auth()->user()->id,
            'room_id' => $id,
            'message' => $letter,
            'liked' => 0,
            'seen' => 0,
            'status' => 1,
            'created_at' => now()
        ]);
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- -------- -------
    // This Method Is For Deleting Message
    // ---- ------ -- --- -------- -------

    public function deleteMessage(Request $request, int $id)
    {
        $room_id = $request->input('room');
        $count = Message::where('id', $id)
            ->where('room_id', $room_id)
            ->where('status', 1)
            ->where('user_id', auth()->user()->id)
            ->update([
                'status' => 0,
                'updated_at' => now()
            ]);
        if (!$count) {
            return response()->json(['error' => true], 404);
        }
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- ---- -------
    // This Method Is For Like Message
    // ---- ------ -- --- ---- -------

    public function likeMessage(Request $request, int $id)
    {
        $room_id = $request->input('room');
        $count = Message::where('id', $id)
            ->where('room_id', $room_id)
            ->where('status', 1)
            ->where('liked', 0)
            ->where('user_id', '!=', auth()->user()->id)
            ->update([
                'liked' => 1,
                'updated_at' => now()
            ]);
        if (!$count) {
            return response()->json(['error' => true], 404);
        }
        return response()->json(['success' => true], 200);
    }

}