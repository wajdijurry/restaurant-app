<?php

namespace App\Repositories;

use App\Models\Quantity;
use Illuminate\Support\Facades\DB;

class IngredientRepository
{
    public function reduceQuantities(array $items)
    {
        $productIds = array_column($items, 'product_id');

        $allProductsQuery = join(' UNION ALL ', array_map(function ($productId) {
            return sprintf('SELECT \'%s\' id', $productId);
        }, $productIds));

        $quantities = Quantity::query()
            ->select([
                'quantities.id as id',
                'p.id as product_id',
                'quantities.quantity as product_quantity',
                'i.id as ingredient_id',
                'i.quantity as ingredient_quantity',
                'i.consumed',
                'i.title as ingredient_title'
            ])
            ->rightJoin('products as p', 'p.id', '=', 'quantities.product_id')
            ->rightJoin('ingredients as i', 'i.id', '=', 'quantities.ingredient_id')
            ->joinSub((string) DB::raw($allProductsQuery), 'ap', 'ap.id', '=', 'p.id')
            ->get();

        $ingredients = $quantities->map->only(['ingredient_id', 'consumed'])
            ->unique()->pluck('consumed', 'ingredient_id')->all();

        $ingredientsThresholds = [];

        foreach ($quantities as $quantity) {
            $ingredients[$quantity->ingredient_id] += $quantity->product_quantity;

            if ($quantity->consumed > $ingredients[$quantity->ingredient_id]) {
                throw new \Exception(sprintf('Quantity exceeded for %s', $quantity->ingredient_title));
            }

            $quantity->ingredient->update(['consumed' => $ingredients[$quantity->ingredient_id]]);

            if ($quantity->consumed / $quantity->ingredient_quantity > 0.5) {
                // notify when below 50%, as requested
                $ingredientsThresholds[$quantity->ingredient->id] = $quantity->ingredient;
            }

        }

        return $ingredientsThresholds;
    }
}