<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Friend;

class FriendSeeder extends Seeder
{
    public function run(): void
    {
        Friend::factory(100)->create();
    }
}