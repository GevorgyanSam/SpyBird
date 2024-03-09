<?php

namespace App\Services\Search;

use App\Actions\LocationAction;
use App\Models\LoginInfo;

class GetNearbyContactsService
{

    // --- ---- -------- --- ------- ------ --------
    // The Main Function For Getting Nearby Contacts
    // --- ---- -------- --- ------- ------ --------

    public function handle($request)
    {
        $location = $this->getLocation($request);
        $users = $this->getUsers($location);
        if ($this->missing($users)) {
            return response()->json(['empty' => true], 200);
        }
        $nearby_contacts = $this->processData($users);
        return response()->json(['data' => $nearby_contacts], 200);
    }

    // ---- ------ -- --- ------- ---- --------
    // This Method Is For Getting User Location
    // ---- ------ -- --- ------- ---- --------

    private function getLocation($request)
    {
        $locationAction = new LocationAction();
        $location = $locationAction($request->ip());
        if (isset($location->message)) {
            $location = "Not Detected";
        } else {
            $location = $location->country_name . ', ' . $location->city;
        }
        return $location;
    }

    // ---- ------ -- --- ------- ------ ----- -- --------
    // This Method Is For Getting Online Users By Location
    // ---- ------ -- --- ------- ------ ----- -- --------

    private function getUsers($location)
    {
        return LoginInfo::select('login_info.status', 'users.id', 'users.name', 'users.avatar', 'users.activity')
            ->where('login_info.location', $location)
            ->where('login_info.status', 1)
            ->where('users.id', '!=', auth()->user()->id)
            ->where('users.status', 1)
            ->where('users.invisible', 0)
            ->whereNotIn('users.id', function ($query) {
                $query->select('user_id')
                    ->from('blocked_users')
                    ->where('status', 1)
                    ->where('blocked_user_id', auth()->user()->id);
            })
            ->inRandomOrder()
            ->limit(10)
            ->join('users', 'login_info.user_id', '=', 'users.id')
            ->get();
    }

    // ---- ------ -- -------- -- ----- ----- --- ------- -- ---
    // This Method Is Designed To Check Users Are Missing Or Not
    // ---- ------ -- -------- -- ----- ----- --- ------- -- ---

    private function missing($users)
    {
        return !count($users) ? true : false;
    }

    // ---- ------ -- --- ---------- ---- ---- -- ----- --- --------
    // This Method Is For Processing User Data To Array For Response
    // ---- ------ -- --- ---------- ---- ---- -- ----- --- --------

    private function processData($users)
    {
        return $users->map(function ($contact) {
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
    }

}