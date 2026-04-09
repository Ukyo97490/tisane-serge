<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orders_today'     => Order::whereDate('created_at', today())->count(),
            'orders_pending'   => Order::where('status', 'en_attente')->count(),
            'orders_confirmed' => Order::where('status', 'confirmee')->count(),
            'orders_ready'     => Order::where('status', 'prete')->count(),
            'revenue_month'    => Order::whereMonth('created_at', now()->month)
                ->whereNotIn('status', ['annulee'])
                ->sum('total'),
            'low_stock'        => Product::where('active', true)->where('stock', '<=', 5)->count(),
        ];

        $recentOrders = Order::with('pickupPoint')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
