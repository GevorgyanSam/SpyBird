<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalAccessTokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'type' => fake()->randomElement(['registration', 'password_reset', 'account_termination', 'enable_two_factor_authentication', 'disable_two_factor_authentication']),
            'token' => fake()->asciify('************************************************************'),
            'status' => fake()->numberBetween(0, 1),
            'created_at' => fake()->dateTime(),
            'expires_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}