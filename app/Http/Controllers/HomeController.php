<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Room;
use App\Models\RoomMemeber;
use App\Models\User;
use App\Services\Block\GetBlockedRelationshipService;
use App\Services\Friends\GetFriendshipStatusService;
use App\Services\Home\CheckAuthenticationService;
use App\Services\Settings\GetLoginHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // ---- ------ -- --- ------- --- -----
    // This Method Is For Getting All Chats
    // ---- ------ -- --- ------- --- -----

    public function getChats()
    {
        $chats = RoomMemeber::select(
            'room_members.room_id',
            'users.name',
            'users.activity',
            'users.avatar',
            'login_info.status',
            'messages.message',
            'messages.created_at',
            DB::raw('(SELECT COUNT(*) FROM messages WHERE messages.room_id = room_members.room_id AND messages.seen = 0 AND messages.user_id != ' . auth()->user()->id . ') as unread_message_count'))
            ->join('users', 'room_members.user_id', '=', 'users.id')
            ->join(DB::raw('(SELECT user_id, MAX(created_at) AS latest_login FROM login_info GROUP BY user_id) as latest_login_info'), function ($join) {
                $join->on('users.id', '=', 'latest_login_info.user_id');
            })
            ->join('login_info', function ($join) {
                $join->on('users.id', '=', 'login_info.user_id')->on('latest_login_info.latest_login', '=', 'login_info.created_at');
            })
            ->join(DB::raw('(SELECT room_id, message, created_at, ROW_NUMBER() OVER (PARTITION BY room_id ORDER BY created_at DESC) AS row_num FROM messages WHERE status = 1) as messages'), function ($join) {
                $join->on('room_members.room_id', '=', 'messages.room_id')->where('messages.row_num', '=', 1);
            })
            ->whereIn('room_members.room_id', function ($query) {
                $query->select('rooms.id')
                    ->from('rooms')
                    ->join('room_members', 'rooms.id', '=', 'room_members.room_id')
                    ->where('rooms.status', '=', 1)
                    ->where('room_members.user_id', '=', auth()->user()->id);
            })
            ->leftJoin('blocked_users', function ($join) {
                $join->on('users.id', '=', 'blocked_users.user_id')
                    ->where('blocked_users.blocked_user_id', '=', auth()->user()->id)
                    ->where('blocked_users.status', '=', 1);
            })
            ->whereNull('blocked_users.id')
            ->where('room_members.user_id', '!=', auth()->user()->id)
            ->where('users.status', '=', 1)
            ->where('users.invisible', '=', 0)
            ->orderByDesc('unread_message_count')
            ->orderByDesc('messages.created_at')
            ->get();
        if (!$chats->count()) {
            return response()->json(['empty' => true], 200);
        }
        return response()->json(['data' => $chats], 200);
    }

    // ---- ------ -- --- --------- -----
    // This Method Is For Searching Chats
    // ---- ------ -- --- --------- -----

    public function searchChats(Request $request)
    {
        $search = $request->input('search');
        $chats = RoomMemeber::select(
            'room_members.room_id',
            'users.name',
            'users.activity',
            'users.avatar',
            'login_info.status',
            'messages.message',
            'messages.created_at',
            DB::raw('(SELECT COUNT(*) FROM messages WHERE messages.room_id = room_members.room_id AND messages.seen = 0 AND messages.user_id != ' . auth()->user()->id . ') as unread_message_count'))
            ->join('users', 'room_members.user_id', '=', 'users.id')
            ->join(DB::raw('(SELECT user_id, MAX(created_at) AS latest_login FROM login_info GROUP BY user_id) as latest_login_info'), function ($join) {
                $join->on('users.id', '=', 'latest_login_info.user_id');
            })
            ->join('login_info', function ($join) {
                $join->on('users.id', '=', 'login_info.user_id')->on('latest_login_info.latest_login', '=', 'login_info.created_at');
            })
            ->join(DB::raw('(SELECT room_id, message, created_at, ROW_NUMBER() OVER (PARTITION BY room_id ORDER BY created_at DESC) AS row_num FROM messages WHERE status = 1) as messages'), function ($join) {
                $join->on('room_members.room_id', '=', 'messages.room_id')->where('messages.row_num', '=', 1);
            })
            ->whereIn('room_members.room_id', function ($query) {
                $query->select('rooms.id')
                    ->from('rooms')
                    ->join('room_members', 'rooms.id', '=', 'room_members.room_id')
                    ->where('rooms.status', '=', 1)
                    ->where('room_members.user_id', '=', auth()->user()->id);
            })
            ->leftJoin('blocked_users', function ($join) {
                $join->on('users.id', '=', 'blocked_users.user_id')
                    ->where('blocked_users.blocked_user_id', '=', auth()->user()->id)
                    ->where('blocked_users.status', '=', 1);
            })
            ->whereNull('blocked_users.id')
            ->where('room_members.user_id', '!=', auth()->user()->id)
            ->where('users.status', '=', 1)
            ->where('users.invisible', '=', 0)
            ->where('users.name', 'like', "%$search%")
            ->orderByDesc('unread_message_count')
            ->orderByDesc('messages.created_at')
            ->get();
        if (!$chats->count()) {
            return response()->json(['empty' => true], 200);
        }
        return response()->json(['data' => $chats], 200);
    }

}