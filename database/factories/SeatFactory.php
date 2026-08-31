<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    public function definition(): array {
        return [
            'studio_id' => Studio::factory(),
            'row' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'number' => $this->faker->numberBetween(1, 10),
        ];
    }
}
