<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomMemeber;

class RoomMemeberSeeder extends Seeder
{
    public function run(): void
    {
        RoomMemeber::factory(100)->create();
    }
}