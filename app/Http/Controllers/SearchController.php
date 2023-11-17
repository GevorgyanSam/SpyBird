<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\LoginInfo;

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
        return response()->json(['data' => $suggested_contacts], 200);
    }

    // ---- ------ -- --- ------- ------ --------
    // This Method Is For Getting Nearby Contacts
    // ---- ------ -- --- ------- ------ --------

    public function getNearbyContacts(Request $request)
    {
        $location = LocationController::find($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        $loginInfo = LoginInfo::with([
            'user' => function ($query) {
                $query->where('status', 1)->where('invisible', 0);
            }
        ])
            ->where('user_id', '!=', auth()->user()->id)
            ->where('location', $location)
            ->where('status', 1)
            ->inRandomOrder()
            ->limit(10)
            ->get();
        if (empty($loginInfo->user)) {
            return response()->json(['empty' => true], 200);
        }
        $nearby_contacts = $loginInfo->map(function ($info) {
            $user = $info->user;
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
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $avatar,
                'status' => $info->status
            ];
        });
        return response()->json(['data' => $nearby_contacts], 200);
    }

}