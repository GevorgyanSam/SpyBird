<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Room;
use App\Models\RoomMemeber;
use App\Models\User;
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
        $client = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'active' => $user->activity && $user->latestLoginInfo->status ? 'active' : null,
            'status' => $user->activity ? ($user->latestLoginInfo->status ? 'online' : Carbon::parse($user->latestLoginInfo->updated_at)->format('d M H:i')) : 'hidden status',
        ];
        $devices = $getLoginHistoryService->handle();
        return view('pages.room', ['devices' => $devices, 'client' => $client]);
    }

}