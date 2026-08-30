<?php

namespace Database\Factories;

use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition(): array {
        return [
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
            'show_date' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'show_time' => $this->faker->time('H:i:s'),
            'price' => $this->faker->randomElement([35000, 40000, 50000]),
        ];
    }
}