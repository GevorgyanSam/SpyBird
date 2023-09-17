<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDataHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'type' => 'name_change',
            'from' => fake()->name(),
            'to' => fake()->name(),
            'created_at' => fake()->dateTime()
        ];
    }
}