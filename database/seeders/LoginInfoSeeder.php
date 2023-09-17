<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoginInfo;

class LoginInfoSeeder extends Seeder
{
    public function run(): void
    {
        LoginInfo::factory(100)->create();
    }
}