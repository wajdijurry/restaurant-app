<?php

namespace App\Models;

use App\Models\Traits\UuidPrimary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $type
 * @property Collection $notifiedIngredients
 */
class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        UuidPrimary,
        SoftDeletes,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'remember_token',
        'deleted_at',
        'email',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function notifiedIngredients()
    {
        return $this->hasMany(IngredientsNotifications::class, 'merchant_id', 'id');
    }

    /**
     * @param Ingredient[] $ingredients
     * @return array
     */
    public function notifyAtLeastFor(array $ingredients)
    {
        $alreadyNotified = array_column($this->notifiedIngredients->all(), null, 'ingredient_id');
        $newIngredients = array_column($ingredients, null, 'id');

        if ($toBeNotified = array_diff_key($newIngredients, $alreadyNotified)) {
            IngredientsNotifications::factory()->createMany(array_map(function ($ingredient) {
                return ['ingredient_id' => $ingredient->id, 'merchant_id' => $ingredient->merchant_id];
            }, $toBeNotified));
            return $toBeNotified;
        }

        return [];
    }

    /**
     * Get user data as array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
