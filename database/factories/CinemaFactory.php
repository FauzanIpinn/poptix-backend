<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CinemaFactory extends Factory
{
    public function definition(): array {
        return [
            'name' => $this->faker->company() . ' Cinema',
            'brand' => $this->faker->randomElement(['XXI', 'CGV', 'Cinepolis']),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
        ];
    }
}