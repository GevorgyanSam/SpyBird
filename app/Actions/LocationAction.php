<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class LocationAction
{

    // ---- ------ -- --- ------- ---- -------- --- -- --------
    // This Method Is For Getting User Location (By IP Address)
    // ---- ------ -- --- ------- ---- -------- --- -- --------

    public function __invoke(string $ip): object
    {
        $key = env("IP_GEOLOCATION_API_KEY");
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$key&ip=$ip";
        $location = Http::get($url)->object();
        return $location;
    }

}