<?php

namespace App\Services\Search;

use App\Models\User;
use Illuminate\Support\Carbon;

class GetSuggestedContactsService
{

    // --- ---- -------- --- ------- --------- --------
    // The Main Function For Getting Suggested Contacts
    // --- ---- -------- --- ------- --------- --------

    public function handle()
    {
        $users = $this->getUsers();
        if ($this->missing($users)) {
            return response()->json(['empty' => true], 200);
        }
        $suggested_contacts = $this->processData($users);
        return response()->json(['data' => $suggested_contacts], 200);
    }

    // ---- ------ -- --- ------- -----
    // This Method Is For Getting Users
    // ---- ------ -- --- ------- -----

    private function getUsers()
    {
        return User::with('latestLoginInfo')
            ->select('id', 'name', 'avatar', 'activity')
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
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
            $avatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
            $date = $user->latestLoginInfo->updated_at ? Carbon::parse($user->latestLoginInfo->updated_at)->format('d M H:i') : null;
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
                'status' => $user->latestLoginInfo->status,
                'updated_at' => $date
            ];
        });
    }

}