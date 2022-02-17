<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $order = Order::factory()->count(20)->create();

        foreach ($order as $key => $value) {
            $reasonCanceled = $faker->sentence();
            if ($order[$key]['status'] == OrderStatus::CANCELED) {
                $order[$key]['status'] = Order::find($value->id)->update([
                    "reason_canceled" => $reasonCanceled
                ]);
            }
        }
    }
}
