<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Models\LoginInfo;
use App\Models\User;

class MakeUser extends Command
{
    protected $signature = 'make:user';

    protected $description = 'Create a new user';

    public function handle()
    {
        $name = $this->ask('New User Name');
        $nameValidator = Validator::make(['name' => $name], [
            'name' => ['required']
        ]);
        if ($nameValidator->fails()) {
            $this->output->write('<fg=white;bg=red> ERROR </> <fg=white>Enter a valid name.</>');
            return false;
        }
        $email = $this->ask('New User Email');
        $emailValidator = Validator::make(['email' => $email], [
            'email' => ['required', 'email:rfc,dns,filter', 'unique:users']
        ]);
        if ($emailValidator->fails()) {
            $this->output->write('<fg=white;bg=red> ERROR </> <fg=white>Enter a valid email address.</>');
            return false;
        }
        $password = $this->secret('New User Password');
        $passwordValidator = Validator::make(['password' => $password], [
            'password' => ['required', 'min:8']
        ]);
        if ($passwordValidator->fails()) {
            $this->output->write('<fg=white;bg=red> ERROR </> <fg=white>Enter a valid password.</>');
            return false;
        }
        $newUser = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'status' => 1,
            'two_factor_authentication' => 0,
            'activity' => 1,
            'invisible' => 0,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);
        LoginInfo::create([
            'user_id' => $newUser->id,
            'ip' => 'Not Detected',
            'user_agent' => 'Not Detected',
            'location' => 'Not Detected',
            'status' => 0,
            'created_at' => now(),
            'expires_at' => now()->addHours(3),
            'updated_at' => now(),
            'deleted_at' => now()
        ]);
        $this->output->write("<fg=white;bg=blue> INFO </> <fg=white>User created successfully.</>");
    }
}