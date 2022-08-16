<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $repository) {
        $this->orderRepository = $repository;
    }

    /**
     * Create order action
     *
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(CreateOrderRequest $request)
    {
        $data['user_id'] = Auth::id();

        foreach ($request->json('products') as $product) {
            $data['products'][] = [
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity']
            ];
        }

        return response(
            $this->orderRepository->create($data)
        );
    }
}
