<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BackupCode;

class BackupCodeSeeder extends Seeder
{
    public function run(): void
    {
        BackupCode::factory(600)->create();
    }
}