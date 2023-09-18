<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GuestSeeder;
use Database\Seeders\LoginInfoSeeder;
use Database\Seeders\UserDataHistorySeeder;
use Database\Seeders\MessageSeeder;
use Database\Seeders\RoomSeeder;
use Database\Seeders\RoomMemeberSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(GuestSeeder::class);
        $this->call(LoginInfoSeeder::class);
        $this->call(UserDataHistorySeeder::class);
        $this->call(MessageSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(RoomMemeberSeeder::class);
    }
}