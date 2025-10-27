<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class StoreController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);
            
        $categories = Category::all();
        
        return view('store.index', compact('products', 'categories'));
    }

    // ADD THIS METHOD FOR CATEGORY FILTERING
    public function filter($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $products = Product::with('category')
            ->where('category_id', $categoryId)
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);
        
        $categories = Category::all();
        
        return view('store.index', compact('products', 'categories', 'category'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        
        // Related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();
        
       return view('store.show', compact('product'));
    }
}