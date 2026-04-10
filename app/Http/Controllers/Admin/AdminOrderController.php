<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user', 'products')
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn($o) => [
                'id'           => $o->id,
                'user'         => $o->user->name,
                'total_amount' => $o->total_amount,
                'status'       => $o->status,
                'items_count'  => $o->products->count(),
                'created_at'   => $o->created_at->format('d M Y'),
            ]);

        return Inertia::render('Admin/Orders', [
            'orders'  => $orders,
            'filters' => $request->only('status'),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('user', 'products');

        return Inertia::render('Admin/OrderDetail', [
            'order' => [
                'id'           => $order->id,
                'status'       => $order->status,
                'total_amount' => $order->total_amount,
                'created_at'   => $order->created_at->format('d M Y'),
                'user'         => [
                    'name'  => $order->user->name,
                    'email' => $order->user->email,
                ],
                'products' => $order->products->map(fn($p) => [
                    'id'         => $p->id,
                    'name'       => $p->name,
                    'sku'        => $p->sku,
                    'quantity'   => $p->pivot->quantity,
                    'unit_price' => $p->pivot->unit_price,
                ]),
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'completed', 'cancelled'])],
        ]);

        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            DB::transaction(function () use ($order) {
                foreach ($order->products as $product) {
                    $product->increment('stock_quantity', $product->pivot->quantity);
                }
                $order->update(['status' => 'cancelled']);
            });
        } else {
            $order->update(['status' => $request->status]);
        }

        return back()->with('success', 'Order status updated.');
    }
}