<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GuestSeeder;
use Database\Seeders\LoginInfoSeeder;
use Database\Seeders\UserDataHistorySeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(GuestSeeder::class);
        $this->call(LoginInfoSeeder::class);
        $this->call(UserDataHistorySeeder::class);
    }
}