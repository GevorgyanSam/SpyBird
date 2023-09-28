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
use Database\Seeders\TwoFactorAuthenticationSeeder;
use Database\Seeders\BackupCodeSeeder;
use Database\Seeders\FriendSeeder;
use Database\Seeders\NotificationSeeder;
use Database\Seeders\BlockedUserSeeder;
use Database\Seeders\PersonalAccessTokenSeeder;
use Database\Seeders\PersonalAccessTokenEventSeeder;

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
        $this->call(TwoFactorAuthenticationSeeder::class);
        $this->call(BackupCodeSeeder::class);
        $this->call(FriendSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(BlockedUserSeeder::class);
        $this->call(PersonalAccessTokenSeeder::class);
        $this->call(PersonalAccessTokenEventSeeder::class);
    }
}