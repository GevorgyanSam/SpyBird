<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{

    // ---- ------ -- --- ------- ---- -------- --- -- --------
    // This Method Is For Getting User Location (By IP Address)
    // ---- ------ -- --- ------- ---- -------- --- -- --------

    public static function find($ip)
    {
        $key = env("IP_GEOLOCATION_API_KEY");
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$key&ip=$ip";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $location = json_decode(curl_exec($curl));
        curl_close($curl);
        return $location;
    }

}