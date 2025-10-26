<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{public function add(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $quantity = $request->quantity ?? 1;

    if ($product->stock < $quantity) {
        return response()->json([
            'success' => false,
            'message' => 'Not enough stock available.'
        ], 400);
    }

    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {
        $cart[$id]['quantity'] += $quantity;
    } else {
        $cart[$id] = [
            "name" => $product->name,
            "price" => $product->price,
            "quantity" => $quantity,
            "image" => $product->image
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'message' => 'Product added to cart!',
        'cart_count' => array_sum(array_column($cart, 'quantity'))
    ]);
}

}
