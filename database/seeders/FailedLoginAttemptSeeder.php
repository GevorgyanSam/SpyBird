<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FailedLoginAttempt;

class FailedLoginAttemptSeeder extends Seeder
{
    public function run(): void
    {
        FailedLoginAttempt::factory(100)->create();
    }
}