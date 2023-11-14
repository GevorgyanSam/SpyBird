<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{

    // ---- ------ -- --- ------- ---- -------- --- -- --------
    // This Method Is For Getting User Location (By IP Address)
    // ---- ------ -- --- ------- ---- -------- --- -- --------

    public static function find($ip)
    {
        $key = env("IP_GEOLOCATION_API_KEY");
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$key&ip=$ip";
        $location = Http::get($url)->object();
        return $location;
    }

}