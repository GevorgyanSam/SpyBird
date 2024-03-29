<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlockedUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'blocked_user_id' => fake()->numberBetween(1, 100),
            'status' => fake()->numberBetween(0, 1),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}