<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalAccessTokenEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'token_id' => fake()->numberBetween(1, 100),
            'type' => fake()->randomElement(['request', 'usage']),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent()
        ];
    }
}