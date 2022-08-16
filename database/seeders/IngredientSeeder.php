<?php

namespace Database\Seeders;

use App\Models\Ingredient;

class IngredientSeeder extends SequentialSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = Ingredient::factory()->createMany([
            [
                'title' => 'Beef',
                'quantity' => 20,
                'merchant_id' => self::$merchant->id
            ],
            [
                'title' => 'Cheese',
                'quantity' => 5,
                'merchant_id' => self::$merchant->id
            ],
            [
                'title' => 'Onion',
                'quantity' => 1,
                'merchant_id' => self::$merchant->id
            ]
        ]);

        self::$ingredients = $ingredients->all();
    }
}
