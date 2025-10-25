<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper method to get cart identifier (user_id or session_id)
    public static function getIdentifier()
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id()];
        }
        
        return ['session_id' => session()->getId()];
    }

    // Get all cart items for current user/session
    public static function getItems()
    {
        $identifier = self::getIdentifier();
        
        return self::with(['product.category', 'product.seller'])
            ->where($identifier)
            ->get();
    }

    // Add item to cart
    public static function addItem($productId, $quantity = 1)
    {
        $identifier = self::getIdentifier();
        
        $cart = self::where($identifier)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            self::create(array_merge($identifier, [
                'product_id' => $productId,
                'quantity' => $quantity
            ]));
        }
    }

    // Remove item from cart
    public static function removeItem($productId)
    {
        $identifier = self::getIdentifier();
        
        self::where($identifier)
            ->where('product_id', $productId)
            ->delete();
    }

    // Clear entire cart
    public static function clearCart()
    {
        $identifier = self::getIdentifier();
        self::where($identifier)->delete();
    }

    // Update quantity
    public static function updateQuantity($productId, $quantity)
    {
        $identifier = self::getIdentifier();
        
        self::where($identifier)
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]);
    }
}