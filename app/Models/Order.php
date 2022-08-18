<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use App\Models\Traits\UuidPrimary;
use App\Observers\OrderItemObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string $merchant_id
 * @property User $merchant
 * @property OrderItem[] $items
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Order extends Model
{
    use HasFactory,
        UuidPrimary,
        SoftDeletes;

    protected $fillable = [
        'id', 'user_id', 'merchant_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();

        self::observe([
            OrderItemObserver::class
        ]);
    }

    /**
     * Get merchant of an order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id', 'id')
            ->where('type', UserTypeEnum::USER_TYPE_MERCHANT);
    }

    /**
     * Get order items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $items = [];

        foreach ($this->items as $item) {
            if (isset($items[$item->product->id])) {
                $items[$item->product->id]['quantity']++;
            } else {
                $items[$item->product->id] = array_merge($item->product->toArray(), ['quantity' => 1]);
            }
        }

        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'merchant' => $this->merchant,
            'items' => array_values($items)
        ];
    }
}
