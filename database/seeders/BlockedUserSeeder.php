<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockedUser;

class BlockedUserSeeder extends Seeder
{
    public function run(): void
    {
        BlockedUser::factory(100)->create();
    }
}