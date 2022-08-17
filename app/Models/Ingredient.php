<?php

namespace App\Models;

use App\Models\Traits\UuidPrimary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $merchant_id
 * @property string $title
 * @property float $quantity
 * @property float $consumed
 */
class Ingredient extends Model
{
    use HasFactory,
        UuidPrimary,
        SoftDeletes;

    protected $fillable = [
        'id', 'name', 'quantity', 'consumed', 'merchant_id'
    ];

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            Quantity::class,
            'ingredient_id',
            'id',
            'id',
            'product_id'
        );
    }
}
