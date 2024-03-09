<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDataHistory;

class UserDataHistorySeeder extends Seeder
{
    public function run(): void
    {
        UserDataHistory::factory(100)->create();
    }
}