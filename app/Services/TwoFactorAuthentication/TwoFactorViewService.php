<?php

namespace App\Services\TwoFactorAuthentication;

use Illuminate\Support\Str;

class TwoFactorViewService
{

    // --- ---- -------- --- --- ---- ----
    // The Main Function For 2FA View Page
    // --- ---- -------- --- --- ---- ----

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
        $this->maskEmail($credentials);
        return view('users.two-factor', ['credentials' => $credentials]);
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
        return $credentials->visited ?? false;
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
        $credentials->visited = true;
    }

    // ---- ------ -- --- ------- ---- -----
    // This Method Is For Masking User Email
    // ---- ------ -- --- ------- ---- -----

    private function maskEmail($credentials)
    {
        $position = Str::position($credentials->email, '@');
        $replacement = substr($credentials->email, 1, $position - 2);
        $masked_email = str_replace($replacement, '*****', $credentials->email);
        $credentials->masked = $masked_email;
    }

}