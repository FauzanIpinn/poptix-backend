<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array {
        return [
            'user_id' => User::factory(),
            'schedule_id' => Schedule::factory(),
            'total_price' => $this->faker->randomElement([35000, 40000, 50000]),
            'status' => 'pending',
            'expires_at' => now()->addMinutes(15),
            'snap_token' => null,
            'payment_type' => null,
            'midtrans_order_id' => null,
            'paid_at' => null,
        ];
    }
}