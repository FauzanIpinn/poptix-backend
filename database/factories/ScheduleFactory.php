<?php

namespace Database\Factories;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition(): array {
        $startsAt = $this->faker->dateTimeBetween('+2 hours', '+1 week');

        return [
            'movie_id' => Movie::factory(),
            'studio_id' => Studio::factory(),
            'show_date' => $startsAt->format('Y-m-d'),
            'show_time' => $startsAt->format('H:i:s'),
            'price' => $this->faker->randomElement([35000, 40000, 50000]),
        ];
    }

    public function configure(): static {
        return $this->afterMaking(function ($schedule) {
            if (! $schedule->studio_id && $schedule->cinema_id) {
                $studio = Studio::where('cinema_id', $schedule->cinema_id)->first()
                    ?? Studio::factory()->create(['cinema_id' => $schedule->cinema_id]);
                $schedule->studio_id = $studio->id;
            }
        });
    }

}