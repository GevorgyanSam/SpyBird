<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Services\Search\GetNearbyContactsService;
use App\Services\Search\GetSuggestedContactsService;

class SearchController extends Controller
{

    // ---- ------ -- --- ------- --------- --------
    // This Method Is For Getting Suggested Contacts
    // ---- ------ -- --- ------- --------- --------

    public function getSuggestedContacts(GetSuggestedContactsService $service)
    {
        return $service->handle();
    }

    // ---- ------ -- --- ------- ------ --------
    // This Method Is For Getting Nearby Contacts
    // ---- ------ -- --- ------- ------ --------

    public function getNearbyContacts(Request $request, GetNearbyContactsService $service)
    {
        return $service->handle($request);
    }

    // ---- ------ -- --- --------- --------
    // This Method Is For Searching Contacts
    // ---- ------ -- --- --------- --------

    public function searchContacts(Request $request)
    {
        $search = $request->input('search');
        $users = User::with('latestLoginInfo')
            ->select('id', 'name', 'avatar', 'activity')
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->where('name', 'like', "%$search%")
            ->inRandomOrder()
            ->limit(10)
            ->get();
        if (!count($users)) {
            return response()->json(['empty' => true], 200);
        }
        $contacts = $users->map(function ($user) {
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
        return response()->json(['data' => $contacts], 200);
    }

}