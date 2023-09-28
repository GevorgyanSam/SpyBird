<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalAccessTokenEvent;

class PersonalAccessTokenEventSeeder extends Seeder
{
    public function run(): void
    {
        PersonalAccessTokenEvent::factory(100)->create();
    }
}