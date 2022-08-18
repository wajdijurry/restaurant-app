<?php

namespace App\Observers;


use App\Models\Order;
use App\Notifications\IngredientQuantityThreshold;
use App\Repositories\IngredientRepository;

class OrderItemObserver
{
    private static IngredientRepository $ingredientRepository;

    private static array $reducedIngredients = [];

    public function __construct(IngredientRepository $ingredientRepository)
    {
        self::$ingredientRepository = $ingredientRepository;
    }

    public static function creating(Order $order)
    {
        self::$reducedIngredients = self::$ingredientRepository->reduceQuantities($order->items->all());
    }
    public static function created(Order $order)
    {
        $merchant = $order->merchant;
        if (self::$reducedIngredients && $notifyFor = $merchant->notifyAtLeastFor(self::$reducedIngredients)) {
            $merchant->notify(new IngredientQuantityThreshold($notifyFor));
        }
    }

}
