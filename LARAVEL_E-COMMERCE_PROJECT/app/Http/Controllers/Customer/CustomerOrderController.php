<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CustomerOrderController extends Controller 
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->with(['items.product'])
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('id', $id)
                     ->where('user_id', Auth::id())
                     ->with(['items.product'])
                     ->firstOrFail();
        
        return view('customer.orders.show', compact('order'));
    }
}