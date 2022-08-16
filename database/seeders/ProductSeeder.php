<?php

namespace Database\Seeders;

use App\Models\Product;

class ProductSeeder extends SequentialSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Product $product */
        $product = Product::factory()->createOne([
            'title' => 'Burger',
            'merchant_id' => self::$merchant->id
        ]);

        self::$productId = $product->id;
    }
}
