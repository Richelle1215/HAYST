<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class SellerOrderController extends Controller
{
    public function index()
    {
        // Get all orders with user relationship
        $orders = Order::with('user')
            ->latest()
            ->get();

        // Statistics using correct column total_amount
        $totalRevenue = Order::sum('total_amount');
        $totalOrders = Order::count();
        $totalReturns = Order::where('status', 'returned')->count();

        return view('seller.orders.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'totalReturns'
        ));
    }

    public function show($id)
    {
        // Load order with user + items + product relationship
        $order = Order::with(['user', 'orderItems.product'])
            ->findOrFail($id);

        return view('seller.orders.show', compact('order'));
    }
}
