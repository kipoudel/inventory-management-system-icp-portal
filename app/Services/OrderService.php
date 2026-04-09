<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function create(int $userId, array $items): Order
    {
        return DB::transaction(function () use ($userId, $items) {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for product: {$product->name}. Available: {$product->stock_quantity}",
                    ]);
                }

                $product->decrement('stock_quantity', $item['quantity']);

                $totalAmount += $product->price * $item['quantity'];

                $orderItems[$product->id] = [
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id'      => $userId,
                'status'       => 'pending',
                'total_amount' => $totalAmount,
            ]);

            $order->products()->attach($orderItems);

            return $order->load('products');
        });
    }

    public function cancel(Order $order): Order
    {
        if ($order->status === 'cancelled') {
            throw ValidationException::withMessages([
                'order' => 'Order is already cancelled.',
            ]);
        }

        DB::transaction(function () use ($order) {
            foreach ($order->products as $product) {
                $product->increment('stock_quantity', $product->pivot->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        return $order->fresh();
    }
}