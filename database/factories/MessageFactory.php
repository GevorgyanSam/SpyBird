<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'room_id' => fake()->numberBetween(1, 100),
            'message' => fake()->sentence(fake()->numberBetween(1, 50)),
            'liked' => fake()->numberBetween(0, 1),
            'status' => fake()->numberBetween(0, 1),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}