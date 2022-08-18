<?php

namespace Tests\Feature;

use App\Enums\UserTypeEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Repositories\IngredientRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private User $merchant;

    private IngredientRepository$ingredientRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->ingredientRepository = new IngredientRepository();
        $this->user = User::query()->where('type', UserTypeEnum::USER_TYPE_CUSTOMER)->first();
        $this->merchant = User::query()->where('type', UserTypeEnum::USER_TYPE_MERCHANT)->first();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'merchant_id' => $this->merchant->id
        ]);

        // Assert that order is created
        $this->assertModelExists($order);
    }

    public function test_create_order_items()
    {
        $product = Product::query()->firstOrFail();

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'merchant_id' => $this->merchant->id
        ]);

        $items = OrderItem::factory(2)->createMany([
            [
                'order_id' => $order->id,
                'product_id' => $product->id
            ]
        ])->first()->all();

        foreach ($items as $item) {
            $this->assertModelExists($item);
        }
    }

    public function test_massive_order_items()
    {
        $product = Product::query()->firstOrFail();

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'merchant_id' => $this->merchant->id
        ]);

        $items = OrderItem::factory(50)->createMany([
            [
                'order_id' => $order->id,
                'product_id' => $product->id
            ]
        ])->first()->all();

        $reducedIngredients = $this->ingredientRepository->reduceQuantities($items);

        $this->assertEquals(1, array_shift($reducedIngredients)->consumed);
    }

    public function test_quantity_exceeded_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Quantity exceeded for Onion');

        $product = Product::query()->firstOrFail();

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'merchant_id' => $this->merchant->id
        ]);

        $items = OrderItem::factory(51)->createMany([
            [
                'order_id' => $order->id,
                'product_id' => $product->id
            ]
        ])->first()->all();

        $this->ingredientRepository->reduceQuantities($items);
    }
}
