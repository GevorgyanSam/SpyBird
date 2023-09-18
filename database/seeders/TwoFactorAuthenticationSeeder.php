<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TwoFactorAuthentication;

class TwoFactorAuthenticationSeeder extends Seeder
{
    public function run(): void
    {
        TwoFactorAuthentication::factory(100)->create();
    }
}