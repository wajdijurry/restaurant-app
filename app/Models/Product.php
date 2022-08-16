<?php

namespace App\Models;

use App\Models\Traits\UuidPrimary;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $merchant_id
 * @property string $title
 * @property Ingredient[] $ingredients
 * @property Quantity[] $quantities
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    use HasFactory,
        UuidPrimary,
        SoftDeletes;

    protected $fillable = [
        'id', 'name'
    ];

    protected $hidden = [
        'deleted_at', 'quantities', 'merchant_id'
    ];

    /**
     * Get product ingredients quantities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quantities()
    {
        return $this->hasMany(Quantity::class, 'product_id', 'id');
    }

    /**
     * Get product ingredients
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function ingredients()
    {
        return $this->hasManyThrough(
            Ingredient::class,
            Quantity::class,
            'product_id',
            'ingredient_id',
            'id',
            'id'
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }
}
