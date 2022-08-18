<?php

namespace Database\Seeders;

use App\Models\OrderItem;

class OrderItemSeeder extends SequentialSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderItem::factory(2)->createMany([
            [
                'order_id' => self::$order->id,
                'product_id' => self::$productId
            ]
        ]);
    }
}
