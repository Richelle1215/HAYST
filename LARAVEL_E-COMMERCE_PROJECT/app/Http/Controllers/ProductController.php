<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. INDEX: Ipakita ang listahan ng Products
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        // Tiyakin na mayroon kang view: resources/views/seller/products/index.blade.php
        return view('seller.products.index', compact('products'));
    }

    // 2. CREATE: Ipakita ang form para sa bagong Product
    public function create()
    {
        $categories = Category::all();
        // Tiyakin na mayroon kang view: resources/views/seller/products/create.blade.php
        return view('seller.products.create', compact('categories'));
    }

    // 3. STORE: I-save ang bagong Product
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }

    // 4. SHOW: Ipakita ang detalye ng isang Product
    public function show(Product $product)
    {
        // Ginamit ang parehong view ng Customer Detail Page para sa simplehan.
        // Tiyakin na mayroon kang view: resources/views/store/show.blade.php
        return view('store.show', compact('product')); 
    }

    // 5. EDIT: Ipakita ang form para i-edit ang Product
    public function edit(Product $product)
    {
        $categories = Category::all();
        // Tiyakin na mayroon kang view: resources/views/seller/products/edit.blade.php
        return view('seller.products.edit', compact('product', 'categories'));
    }

    // 6. UPDATE: I-update ang Product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            // Burahin ang lumang image kung meron
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // I-upload ang bagong image
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully.');
    }

    // 7. DESTROY: Burahin/I-delete ang Product
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }
}