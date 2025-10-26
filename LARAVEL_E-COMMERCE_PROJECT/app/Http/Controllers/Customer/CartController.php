<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if enough stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available!'
            ], 400);
        }

        // Check if product already in cart
        $cartItem = Cart::where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available!'
                ], 400);
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        $cartCount = Cart::where('user_id', auth()->id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function index()
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', auth()->id())
                       ->where('id', $id)
                       ->firstOrFail();

        $product = $cartItem->product;

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available!'
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Calculate item total
        $itemTotal = $product->price * $cartItem->quantity;
        
        // Calculate cart total
        $cartTotal = Cart::where('user_id', auth()->id())
            ->get()
            ->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal
        ]);
    }

    public function remove($id)
    {
        $cartItem = Cart::where('user_id', auth()->id())
                       ->where('id', $id)
                       ->firstOrFail();

        $cartItem->delete();

        // Calculate new cart total
        $cartTotal = Cart::where('user_id', auth()->id())
            ->get()
            ->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart!',
            'cart_total' => $cartTotal
        ]);
    }
}