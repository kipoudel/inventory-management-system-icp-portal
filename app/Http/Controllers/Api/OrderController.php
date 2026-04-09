<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function index()
    {
        $orders = Order::with('products')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->create(auth()->id(), $request->validated()['items']);
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order->load('products'));
    }

    public function cancel(Order $order)
    {
        $order = $this->orderService->cancel($order);
        return new OrderResource($order);
    }
}