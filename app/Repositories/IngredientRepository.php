<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Models\OrderItem;

class IngredientRepository
{
    /**
     * Reduce ingredients quantity and send email to merchant
     *
     * @param OrderItem[] $items
     * @return array
     * @throws \Throwable
     */
    public function reduceQuantities(array $items)
    {
        $products = $quantities = $totalQuantity = $ingredientThresholds = [];

        foreach ($items as $item) {
            $products[] = $item->product;
        }

        foreach ($products as $product) {
            $quantities[] = $product->quantities->all();
        }

        foreach ($quantities as $quantity) {
            $totalQuantity[] = array_column($quantity, 'quantity', 'ingredient_id');
        }

        $ingredientsIds = array_unique(array_merge(...array_map(function ($productIngredients) {
            return array_keys($productIngredients);
        }, $totalQuantity)));

        // Initialize ingredients array
        $quantitySum = array_combine(
            array_reverse($ingredientsIds),
            array_fill(0, count($ingredientsIds), 0)
        );

        array_reduce($totalQuantity, function ($initial, $quantity) use (&$quantitySum) {
            foreach ($quantity as $ingredientId => $quantum) {
                $quantitySum[$ingredientId] += $quantum;
            }
        });

        /** @var Ingredient[] $ingredients */
        $ingredients = Ingredient::query()
            ->whereIn('id', $ingredientsIds)
            ->get()
            ->all();

        foreach ($ingredients as $ingredient) {
            $ingredient->consumed += $quantitySum[$ingredient->id];

            if ($ingredient->consumed > $ingredient->quantity) {
                throw new \Exception(sprintf('Quantity exceeded for %s', $ingredient->title));
            }

            $ingredient->updateOrFail();

            if ($ingredient->consumed / $ingredient->quantity > 0.5) {
                // notify when below 50%, as requested
                $ingredientThresholds[] = $ingredient;
            }
        }

        return $ingredientThresholds;
    }
}