<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull(),
            'order_code' => Str::random(8),
            'total_price' => 10000000,
            'status' => $this->faker->numberBetween(0,4),
            'reason_canceled' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
