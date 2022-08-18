<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * The purpose of this class to pass parameters among seeders
 */
abstract class SequentialSeeder extends Seeder
{
    /**
     * @var string
     */
    protected static $productId;

    /**
     * @var Ingredient[]
     */
    protected static $ingredients = [];

    /**
     * @var User
     */
    protected static $merchant;

    /**
     * @var User
     */
    protected static $user;

    /**
     * @var Order
     */
    protected static $order;
}