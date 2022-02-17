<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();

        return [
            'category_id' => Category::all()->random()->id,
            'title' => $title,
            'content' => $this->faker->paragraph(),
            'description' => $title,
            'image' => $this->faker->imageUrl($width = 360, $height = 360),
            'slug' => Str::slug($title, '-'),
            'quantity' => 1000,
            // 'sold' => 0,
            'retail_price' => $this->faker->numberBetween(800000, 1000000),
            'original_price' => $this->faker->numberBetween(500000, 700000),
            'avg_rate' => $this->faker->numberBetween(3,5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
