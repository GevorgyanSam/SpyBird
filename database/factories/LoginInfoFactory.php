<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoginInfoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'status' => fake()->numberBetween(0, 1),
            'created_at' => fake()->dateTime(),
            'expires_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
            'deleted_at' => fake()->dateTime()
        ];
    }
}