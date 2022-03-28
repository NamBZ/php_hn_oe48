<?php

namespace Database\Seeders;

use File;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/products.json");
        $products = json_decode($json);

        foreach ($products as $product) {
            Product::create([
                "id" => $product->id,
                "title" => $product->title,
                "content" => $product->content,
                "description" => $product->description,
                "image" => $product->image,
                "slug" => $product->slug,
                "quantity" => $product->quantity,
                "sold" => $product->sold,
                "retail_price" => $product->retail_price,
                "original_price" => $product->original_price,
                "avg_rate" => $product->avg_rate,
                "category_id" => $product->category_id,
            ]);
        }
    }
}
