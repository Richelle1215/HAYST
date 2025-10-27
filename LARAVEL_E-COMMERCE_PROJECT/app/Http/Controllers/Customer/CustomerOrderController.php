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
public function cancel($orderId)
{
    $order = Order::find($orderId);
    
    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }
    
    // Check both user_id and customer_id fields
    $userId = auth()->id();
    $isOwner = ($order->user_id == $userId) || ($order->customer_id == $userId);
    
    if (!$isOwner) {
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to cancel this order'
        ], 403);
    }

    // Check if order can be cancelled (only pending and processing)
    if (!in_array($order->status, ['pending', 'processing'])) {
        return response()->json([
            'success' => false,
            'message' => 'Only pending or processing orders can be cancelled'
        ], 400);
    }

    // Update order status to cancelled
    $order->status = 'cancelled';
    $order->save();

    // Restore product stock
    foreach ($order->items as $item) {
        if ($item->product) {
            $item->product->increment('stock', $item->quantity);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Order cancelled successfully'
    ]);
}
}