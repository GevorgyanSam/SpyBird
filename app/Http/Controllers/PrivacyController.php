<?php

namespace App\Http\Controllers;

class PrivacyController extends Controller
{

    // ---- ------ -- --- ------- ------ ---- ----
    // This Method Is For Privacy Policy Page View
    // ---- ------ -- --- ------- ------ ---- ----

    public function policy ()
    {
        return view('privacy.policy');
    }

    // ---- ------ -- --- ----- -- ------- ---- ----
    // This Method Is For Terms Of Service Page View
    // ---- ------ -- --- ----- -- ------- ---- ----

    public function terms ()
    {
        return view('privacy.terms');
    }

}