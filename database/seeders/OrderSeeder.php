<?php

namespace Database\Seeders;

use App\Models\Order;

class OrderSeeder extends SequentialSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = Order::factory()->createOne([
            'user_id' => self::$user->id,
            'merchant_id' => self::$merchant->id
        ]);

        self::$order = $order;
    }
}
