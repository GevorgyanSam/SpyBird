<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomMemeberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'room_id' => fake()->numberBetween(1, 100),
            'created_at' => fake()->dateTime()
        ];
    }
}