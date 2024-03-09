<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        $this->call(FailedLoginAttemptSeeder::class);
    }
}