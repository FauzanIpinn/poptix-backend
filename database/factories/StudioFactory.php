<?php

namespace Database\Factories;

use App\Models\Cinema;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Studio>
 */
class StudioFactory extends Factory
{
    public function definition(): array {
        return [
            'cinema_id' => Cinema::factory(),
            'name' => 'Studio ' . $this->faker->unique()->numberBetween(1, 20),
        ];
    }
}
