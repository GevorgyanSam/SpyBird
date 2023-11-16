<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;

class SearchController extends Controller
{

    // ---- ------ -- --- ------- --------- --------
    // This Method Is For Getting Suggested Contacts
    // ---- ------ -- --- ------- --------- --------

    public function getSuggestedContacts()
    {
        $users = User::with('latestLoginInfo')
            ->select(['id', 'name', 'avatar', 'activity'])
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->inRandomOrder()
            ->limit(10)
            ->get();
        $suggested_contacts = $users->map(function ($user) {
            $avatar = $user->avatar;
            if ($avatar) {
                $avatar = asset('storage/' . $user->avatar);
            }
            if (!$user->activity) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $avatar,
                    'hidden' => true
                ];
            }
            $date = optional($user->latestLoginInfo)->updated_at;
            if ($date) {
                $date = Carbon::parse($date)->format('d M H:i');
            }
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $avatar,
                'status' => optional($user->latestLoginInfo)->status,
                'updated_at' => $date
            ];
        });
        return response()->json($suggested_contacts, 200);
    }

    // ---- ------ -- --- ------- ------ --------
    // This Method Is For Getting Nearby Contacts
    // ---- ------ -- --- ------- ------ --------

    public function getNearbyContacts()
    {
        $users = User::with('latestLoginInfo')
            ->select(['id', 'name', 'avatar', 'activity'])
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->inRandomOrder()
            ->limit(10)
            ->get();
        $nearby_contacts = $users->map(function ($user) {
            $avatar = $user->avatar;
            if ($avatar) {
                $avatar = asset('storage/' . $user->avatar);
            }
            if (!$user->activity) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $avatar,
                    'hidden' => true
                ];
            }
            $date = optional($user->latestLoginInfo)->updated_at;
            if ($date) {
                $date = Carbon::parse($date)->format('d M H:i');
            }
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $avatar,
                'status' => optional($user->latestLoginInfo)->status,
                'updated_at' => $date
            ];
        });
        return response()->json($nearby_contacts, 200);
    }

}