<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_products'  => Product::count(),
                'total_orders'    => Order::count(),
                'pending_orders'  => Order::where('status', 'pending')->count(),
                'low_stock'       => Product::where('stock_quantity', '<', 5)->count(),
                'revenue'         => Order::where('status', 'completed')->sum('total_amount'),
            ],
            'recent_orders' => Order::with('user', 'products')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($o) => [
                    'id'           => $o->id,
                    'user'         => $o->user->name,
                    'total_amount' => $o->total_amount,
                    'status'       => $o->status,
                    'items_count'  => $o->products->count(),
                    'created_at'   => $o->created_at->format('d M Y'),
                ]),
        ]);
    }
}