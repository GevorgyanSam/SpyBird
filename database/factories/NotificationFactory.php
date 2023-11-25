<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'sender_id' => fake()->numberBetween(1, 100),
            'type' => fake()->randomElement(['friend_request', 'password_change', 'name_change', 'avatar_change']),
            'content' => fake()->sentence(4),
            'seen' => fake()->numberBetween(0, 1),
            'status' => fake()->numberBetween(0, 1),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}