<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        try {
            $quantity = $request->input('quantity', 1);
            
            // Check stock
            if ($product->stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available.'
                ]);
            }

            // Check if adding more would exceed stock
            $identifier = Cart::getIdentifier();
            $existingCart = Cart::where($identifier)
                ->where('product_id', $product->id)
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $quantity;
                if ($newQuantity > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. Stock limit reached.'
                    ]);
                }
            }

            // Add to cart
            Cart::addItem($product->id, $quantity);

            // Get cart count
            $cartCount = Cart::where($identifier)->count();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function index()
    {
        $cartItems = Cart::getItems();
        
        // Format cart data
        $cart = [];
        foreach ($cartItems as $item) {
            $product = $item->product;
            
            // Load seller relationship to get shop_name
            $product->load('seller');
            
            $shopName = $product->seller 
                ? $product->seller->shop_name 
                : ($product->shop_name ?? 'LUMIÈRE Main Store');
            
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'image' => $product->image,
                'stock' => $product->stock,
                'category' => $product->category->name ?? 'Uncategorized',
                'shop_name' => $shopName,
                'seller_id' => $product->seller_id ?? null
            ];
        }
        
        // Group products by shop
        $groupedCart = [];
        foreach ($cart as $id => $item) {
            $shopName = $item['shop_name'] ?? 'Unknown Shop';
            if (!isset($groupedCart[$shopName])) {
                $groupedCart[$shopName] = [];
            }
            $groupedCart[$shopName][$id] = $item;
        }
        
        return view('cart.index', compact('cart', 'groupedCart'));
    }
    
    public function checkout()
    {
        $cartItems = Cart::getItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }
        
        // Format cart data
        $cart = [];
        foreach ($cartItems as $item) {
            $product = $item->product;
            $product->load('seller');
            
            $shopName = $product->seller 
                ? $product->seller->shop_name 
                : ($product->shop_name ?? 'LUMIÈRE Main Store');
            
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'image' => $product->image,
                'stock' => $product->stock,
                'category' => $product->category->name ?? 'Uncategorized',
                'shop_name' => $shopName,
                'seller_id' => $product->seller_id ?? null
            ];
        }
        
        // Group products by shop for checkout
        $groupedCart = [];
        foreach ($cart as $id => $item) {
            $shopName = $item['shop_name'] ?? 'Unknown Shop';
            if (!isset($groupedCart[$shopName])) {
                $groupedCart[$shopName] = [];
            }
            $groupedCart[$shopName][$id] = $item;
        }
        
        return view('cart.checkout', compact('cart', 'groupedCart'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            if ($request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity exceeds available stock.'
                ]);
            }
            
            Cart::updateQuantity($id, $request->quantity);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart.'
            ]);
        }
    }
    
    public function remove($id)
    {
        try {
            Cart::removeItem($id);
            
            $identifier = Cart::getIdentifier();
            $cartCount = Cart::where($identifier)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart.',
                'cart_count' => $cartCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing item from cart.'
            ]);
        }
    }
    
    public function clear()
    {
        Cart::clearCart();
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
}