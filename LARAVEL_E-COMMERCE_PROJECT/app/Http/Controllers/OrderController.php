<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // ✅ make sure you have an Order model

class OrderController extends Controller
{
    /**
     * Display a listing of seller's orders.
     */
    public function index()
    {
        // Example: get orders belonging to the authenticated seller
        $orders = Order::where('seller_id', auth()->id())->latest()->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Show details of a specific order.
     */
    public function show($id)
    {
        $order = Order::where('seller_id', auth()->id())->findOrFail($id);

        return view('seller.orders.show', compact('order'));
    }
}
