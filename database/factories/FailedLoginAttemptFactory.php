<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FailedLoginAttemptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'type' => fake()->randomElement(['password', 'two_factor_code', 'backup_code']),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTime()
        ];
    }
}