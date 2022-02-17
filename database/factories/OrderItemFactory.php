<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product_id = Product::all()->random()->id;

        return [
            'order_id' => Order::all()->random()->id,
            'product_id' => $product_id,
            'quantity' => 1,
            'price' =>Product::find($product_id)->retail_price,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
