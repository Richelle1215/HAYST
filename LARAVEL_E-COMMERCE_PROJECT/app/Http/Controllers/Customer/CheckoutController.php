<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')
                           ->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return view('customer.checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'contact_number' => 'required|string',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Get cart items
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')
                           ->with('error', 'Your cart is empty!');
        }

        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            // Check stock availability
            if ($item->product->stock < $item->quantity) {
                return redirect()->back()
                               ->with('error', "Insufficient stock for {$item->product->name}");
            }
            $total += $item->product->price * $item->quantity;
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'contact_number' => $request->contact_number,
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Create order items and reduce stock
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Reduce product stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('customer.cart.index')
                       ->with('success', "Order #{$order->order_number} placed successfully! 🎉");
    }
}