<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // <-- ITO ANG NAWAWALA!

class OrderController extends Controller
{
    public function index()
    {
        // Get all orders with relationships
        $orders = Order::with('user')
            ->latest()
            ->get();

        // Calculate statistics
        $totalRevenue = Order::sum('total');
        $totalOrders = Order::count();
        $totalReturns = Order::where('status', 'returned')->count();

        return view('seller.orders.index', compact( // Tiyakin na seller.orders.index ang view path mo
            'orders',
            'totalRevenue',
            'totalOrders',
            'totalReturns'
        ));
    }
}