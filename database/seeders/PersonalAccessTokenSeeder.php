<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalAccessToken;

class PersonalAccessTokenSeeder extends Seeder
{
    public function run(): void
    {
        PersonalAccessToken::factory(100)->create();
    }
}