<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orderId = Order::all()->random()->id;
        $orderItem = OrderItem::find($orderId);
        return [
            'order_id' => $orderId,
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem,
            'rate' => $this->faker->numberBetween(3,5),
            'comment' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
