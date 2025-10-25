<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'shop_name',
        'seller_id',
        'image'
    ];

    // Auto-generate slug when creating/updating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Make slug unique by appending number if needed
                $originalSlug = $product->slug;
                $count = 1;
                
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
                
                // Make slug unique
                $originalSlug = $product->slug;
                $count = 1;
                
                while (static::where('slug', $product->slug)
                    ->where('id', '!=', $product->id)
                    ->exists()) {
                    $product->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }

    // ✅ Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function show($id)
{
    $product = Product::with(['category', 'seller'])->findOrFail($id);
    return view('products.show', compact('product'));
}
    
}