<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $product_id
 * @property string $ingredient_id
 * @property float $quantity
 */
class Quantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'ingredient_id', 'quantity'
    ];
}
