<?php

namespace App\Services\Search;

use App\Models\User;
use Illuminate\Support\Carbon;

class SearchContactsService
{

    // --- ---- ------ --- --------- --------
    // The Main Method For Searching Contacts
    // --- ---- ------ --- --------- --------

    public function handle($request)
    {
        $search = $request->input('search');
        $users = $this->getUsers($search);
        if ($this->missing($users)) {
            return response()->json(['empty' => true], 200);
        }
        $contacts = $this->processData($users);
        return response()->json(['data' => $contacts], 200);
    }

    // ---- ------ -- --- ------- ----- -- ----
    // This Method Is For Getting Users By Name
    // ---- ------ -- --- ------- ----- -- ----

    private function getUsers($search)
    {
        return User::with('latestLoginInfo')
            ->select('id', 'name', 'avatar', 'activity')
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->where('name', 'like', "%$search%")
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')
                    ->from('blocked_users')
                    ->where('status', 1)
                    ->where('blocked_user_id', auth()->user()->id);
            })
            ->inRandomOrder()
            ->limit(10)
            ->get();
    }

    // ---- ------ -- --- -------- --- ----- -----
    // This Method Is For Checking For Empty Users
    // ---- ------ -- --- -------- --- ----- -----

    private function missing($users)
    {
        return !count($users) ? true : false;
    }

    // ---- ------ -- --- ---------- ---- ---- -- ----- --- --------
    // This Method Is For Processing User Data To Array For Response
    // ---- ------ -- --- ---------- ---- ---- -- ----- --- --------

    private function processData($users)
    {
        return $users->map(function ($user) {
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
    }

}