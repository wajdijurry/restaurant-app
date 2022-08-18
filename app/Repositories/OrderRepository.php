<?php

namespace App\Repositories;

use App\Enums\UserTypeEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderRepository
{
    /**
     * @param array $data
     * @return Order
     * @throws \Throwable
     */
    public function create(array $data)
    {
        try {
            $order = DB::transaction(function () use ($data) {
                $merchant = User::query() // Demo purposes
                    ->where('type', UserTypeEnum::USER_TYPE_MERCHANT)
                    ->first();

                $order = new Order([
                    'id' => Str::uuid()->toString(),
                    'user_id' => $data['user_id'],
                    'merchant_id' => $merchant->id
                ]);

                $items = [];

                foreach ($data['products'] as $product) {
                    $items = array_merge($items, array_fill(0, $product['quantity'], [
                        'order_id' => $order->id,
                        'product_id' => $product['product_id']
                    ]));
                }

                $order->setRelation('items', $order->items()->createMany($items));
                $order->saveOrFail();

                return $order;
            });

            DB::commit();

            return $order;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}