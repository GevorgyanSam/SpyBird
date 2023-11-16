<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->freeEmail(),
            'avatar' => fake()->imageUrl(200, 200, 'animals', true),
            'password' => 'qweqweqwe',
            'status' => fake()->numberBetween(0, 1),
            'two_factor_authentication' => fake()->numberBetween(0, 1),
            'activity' => fake()->numberBetween(0, 1),
            'invisible' => fake()->numberBetween(0, 1),
            'email_verified_at' => fake()->dateTime(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}