<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $order_id
 * @property string $product_id
 * @property Order $order
 * @property Product $product
 */
class OrderItem extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'order_id', 'product_id'
    ];

    protected $hidden = [
        'order_id', 'deleted_at'
    ];

    /**
     * Get order for an item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Get product related to order item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
