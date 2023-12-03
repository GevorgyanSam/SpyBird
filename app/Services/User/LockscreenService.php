<?php

namespace App\Services\User;

use App\Models\FailedLoginAttempt;
use Illuminate\Support\Facades\Hash;

class LockscreenService
{

    protected const MAX_LOGIN_ATTEMPTS = 5;

    // --- ---- -------- --- ----------
    // The Main Function For Lockscreen
    // --- ---- -------- --- ----------

    public function handle($request)
    {
        $count = $this->failedLoginAttemptCheck($request);
        if ($count >= self::MAX_LOGIN_ATTEMPTS) {
            return response()->json(['errors' => ['title' => 'Too Many Requests', 'body' => 'Try Again After A While']], 429);
        }
        $this->validate($request);
        $check = Hash::check($request->input('password'), auth()->user()->password);
        if (!$check) {
            $this->createFailedLoginAttempt($request);
            return response()->json(['errors' => ['password' => ['Wrong Password']]], 422);
        }
        $this->forget();
        return response()->json(['success' => true], 200);
    }

    // ---- ------ -- --- -------- --- ------ ----- --------
    // This Method Is For Checking For Failed Login Attempts
    // ---- ------ -- --- -------- --- ------ ----- --------

    protected function failedLoginAttemptCheck($request)
    {
        return FailedLoginAttempt::where('ip', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->where('created_at', '>', now()->subHours(1))
            ->count();
    }

    // ---- ------ -- --- ---------- ---- ----
    // This Method Is For Validating User Data
    // ---- ------ -- --- ---------- ---- ----

    protected function validate($request)
    {
        $rules = [
            'password' => ['bail', 'required', 'min:8']
        ];
        $messages = [
            'required' => 'enter :attribute',
            'min' => 'at least :min characters',
        ];
        $request->validate($rules, $messages);
    }

    // ---- ------ -- --- -------- ------ ----- -------
    // This Method Is For Creating Failed Login Attempt
    // ---- ------ -- --- -------- ------ ----- -------

    protected function createFailedLoginAttempt($request)
    {
        FailedLoginAttempt::create([
            'user_id' => auth()->user()->id,
            'type' => 'password',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()
        ]);
    }

    // ---- ------ -- --- -------- ---- ------ ---- -------
    // This Method Is For Deleting Lock Screen From Session
    // ---- ------ -- --- -------- ---- ------ ---- -------

    protected function forget()
    {
        session()->forget('lockscreen');
    }

}