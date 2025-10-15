<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;


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
public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // ✅ Update text fields
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->stock = $request->stock;
    $product->category_id = $request->category_id;

    // ✅ Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Store new image inside storage/app/public/products
        $path = $request->file('image')->store('products', 'public');

        // Save path to DB
        $product->image = $path;
    }

    $product->save();

    return redirect()
        ->route('seller.products.index')
        ->with('success', 'Product updated successfully!');
}

    // 7. DESTROY: Burahin/I-delete ang Product
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('seller.products.index')
                         ->with('success', 'Product deleted successfully.');
    }
}
