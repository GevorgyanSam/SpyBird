<?php

namespace App\Services\TwoFactorAuthentication;

class LostEmailViewService
{

    // --- ---- -------- --- ---- ----- ---- ----
    // The Main Function For Lost Email View Page
    // --- ---- -------- --- ---- ----- ---- ----

    public function handle()
    {
        if ($this->missingCredentials()) {
            return redirect()->route('user.login');
        }
        $credentials = $this->getCredentials();
        if ($this->isVisited($credentials)) {
            $this->forgetCredentials();
            return redirect()->route('user.login');
        }
        $this->setVisited($credentials);
        return view('users.lost-email', ['credentials' => $credentials]);
    }

    // ---- ------ -- --- --------- -----------
    // This Method Is For Verifying Credentials
    // ---- ------ -- --- --------- -----------

    private function missingCredentials()
    {
        return session()->missing('credentials');
    }

    // ---- ------ -- --- ------- -----------
    // This Method Is For Getting Credentials
    // ---- ------ -- --- ------- -----------

    private function getCredentials()
    {
        return session()->get('credentials');
    }

    // ---- ------ -- -------- -- ----- ------- --- ---- --- --------- --- ---- -- ---
    // This Method Is Designed To Check Whether The User Has Refreshed The Page Or Not
    // ---- ------ -- -------- -- ----- ------- --- ---- --- --------- --- ---- -- ---

    private function isVisited($credentials)
    {
        return $credentials->lost_email ?? false;
    }

    // ---- ------ -- --- ---------- ---- -----------
    // This Method Is For Forgetting User Credentials
    // ---- ------ -- --- ---------- ---- -----------

    private function forgetCredentials()
    {
        session()->forget('credentials');
    }

    // ---- ------ -- --- ------- --- ---- -- -------
    // This Method Is For Setting The Page As Visited
    // ---- ------ -- --- ------- --- ---- -- -------

    private function setVisited($credentials)
    {
        $credentials->lost_email = true;
    }

}