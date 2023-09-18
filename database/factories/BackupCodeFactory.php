<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BackupCodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'code' => fake()->numberBetween(100000, 999999),
            'status' => fake()->numberBetween(0, 1),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}