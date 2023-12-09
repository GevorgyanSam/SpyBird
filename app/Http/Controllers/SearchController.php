<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Search\GetNearbyContactsService;
use App\Services\Search\GetSuggestedContactsService;
use App\Services\Search\SearchContactsService;

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

    public function searchContacts(Request $request, SearchContactsService $service)
    {
        return $service->handle($request);
    }

}