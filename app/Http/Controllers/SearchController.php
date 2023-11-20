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
        if (!count($users)) {
            return response()->json(['empty' => true], 200);
        }
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
            $date = $user->latestLoginInfo->updated_at;
            if ($date) {
                $date = Carbon::parse($date)->format('d M H:i');
            }
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $avatar,
                'status' => $user->latestLoginInfo->status,
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
        $loginInfo = LoginInfo::select('login_info.status', 'users.id', 'users.name', 'users.avatar', 'users.activity')
            ->where('login_info.location', $location)
            ->where('login_info.status', 1)
            ->whereHas('user', function ($query) {
                $query->where('id', '!=', auth()->user()->id)
                    ->where('status', 1)
                    ->where('invisible', 0);
            })
            ->join('users', 'login_info.user_id', '=', 'users.id')
            ->inRandomOrder()
            ->limit(10)
            ->get();
        if (!count($loginInfo)) {
            return response()->json(['empty' => true], 200);
        }
        $nearby_contacts = $loginInfo->map(function ($contact) {
            $avatar = $contact->avatar;
            if ($avatar) {
                $avatar = asset('storage/' . $contact->avatar);
            }
            if (!$contact->activity) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'avatar' => $avatar,
                    'hidden' => true
                ];
            }
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'avatar' => $avatar,
                'status' => $contact->status
            ];
        });
        return response()->json(['data' => $nearby_contacts], 200);
    }

}