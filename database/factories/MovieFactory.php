<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array {
        return [
            'title' => $this->faker->sentence(3),
            'poster' => null,
            'synopsis' => $this->faker->paragraph(),
            'genre' => $this->faker->randomElement(['Action', 'Drama', 'Comedy', 'Horror']),
            'duration' => $this->faker->numberBetween(90, 150),
            'rating' => $this->faker->randomElement(['SU', '13+', '17+', '21+']),
            'trailer' => null,
            'status' => 'now_showing',
        ];
    }
}