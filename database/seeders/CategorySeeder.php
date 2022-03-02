<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        DB::table('categories')->insert([
            'name' => 'danh muc sach',
            'slug' => 'danh-muc-sach',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $categories = Category::factory()->count(10)->create();
    }
}
