<?php

namespace Database\Seeders;

use App\Models\Quantity;

class QuantitySeeder extends SequentialSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Quantity::factory()->createMany([
            [
                'product_id' => self::$productId,
                'ingredient_id' => self::$ingredients[0]->id,
                'quantity' => 0.15
            ],
            [
                'product_id' => self::$productId,
                'ingredient_id' => self::$ingredients[1]->id,
                'quantity' => 0.03
            ],
            [
                'product_id' => self::$productId,
                'ingredient_id' => self::$ingredients[2]->id,
                'quantity' => 0.02
            ]
        ]);
    }
}
