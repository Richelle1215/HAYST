<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch all products with their related category
        $products = Product::with('category')->paginate(10);

        // Fetch all categories for the dropdown
        $categories = Category::all();

        // Pass both variables to the view
        return view('seller.products.index', compact('products', 'categories'));
    }
}
