<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{

    // ---- ------ -- --- ------- --------- --------
    // This Method Is For Getting Suggested Contacts
    // ---- ------ -- --- ------- --------- --------

    public function getSuggestedContacts()
    {
        $suggested_contacts = User::select(['id', 'name', 'avatar', 'activity'])
            ->where('id', '!=', auth()->user()->id)
            ->where('status', 1)
            ->where('invisible', 0)
            ->inRandomOrder()
            ->limit(10)
            ->get();
        return response()->json($suggested_contacts, 200);
    }

}