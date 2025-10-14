<?php

namespace App\Http\Controllers;

use App\Models\Category; // Kailangan ito para ma-access ang Category model
use App\Models\Product;  // Kailangan ito para ma-access ang Product model
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Ipakita ang Product listing page (Grid View).
     * Ito ang gagamitin sa home page.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Kukunin ang lahat ng products, isasama ang category name (with('category')), 
        // at i-paginate (12 items per page)
        $products = Product::with('category')->paginate(12);
        
        // 2. Kukunin ang lahat ng categories para sa filter sidebar
        $categories = Category::all();

        // 3. I-render ang view: resources/views/store/index.blade.php
        return view('store.index', compact('products', 'categories'));
    }

    /**
     * Ipakita ang Product Detail Page.
     *
     * @param  \App\Models\Product  $product (Awtomatikong kukunin ng Laravel ang Product base sa ID sa URL)
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // 1. I-render ang view: resources/views/store/show.blade.php
        return view('store.show', compact('product'));
    }

    /**
     * Ipakita ang Product listing page na naka-filter ayon sa Category.
     *
     * @param  \App\Models\Category  $category (Awtomatikong kukunin ng Laravel ang Category base sa ID sa URL)
     * @return \Illuminate\View\View
     */
    public function filter(Category $category)
    {
        // 1. Kukunin ang products na kabilang lang sa category na ito (products()), 
        // at i-paginate (12 items per page)
        $products = $category->products()->paginate(12);
        
        // 2. Kukunin ang lahat ng categories para sa filter sidebar
        $categories = Category::all();
        
        // 3. Ipasa ang kasalukuyang category para sa heading o indicator
        $currentCategory = $category;

        // 4. I-render ang parehong index view (resources/views/store/index.blade.php)
        return view('store.index', compact('products', 'categories', 'currentCategory'));
    }
}