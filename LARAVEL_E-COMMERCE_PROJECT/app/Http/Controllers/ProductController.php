<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;

class ProductController extends Controller
{
    /**
     * 1. INDEX: Display a listing of products
     */
    public function index()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        if (!$seller) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You must have a seller profile to view products.');
        }

        $products = Product::where('seller_id', $seller->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    /**
     * 2. CREATE: Show the form for creating a new product
     */
    public function create()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You must have a seller profile to create products.');
        }
        
        $categories = Category::where('seller_id', $seller->id)->get(); 
        
        if ($categories->isEmpty()) {
            return redirect()->route('seller.categories.create')
                ->with('info', 'Please create at least one category first before adding products.');
        }
        
        return view('seller.products.create', compact('categories')); 
    }

    /**
     * 3. STORE: Store a newly created product in storage
     */
    public function store(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You must have a seller profile to create products.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0', 
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Use the seller table ID, not the user_id
        $validated['seller_id'] = $seller->id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = null;
        }

        Product::create($validated);
        
        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * 4. SHOW: Display the specified product
     */
    public function show(Product $product)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller || $product->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.products.show', compact('product'));
    }

    /**
     * 5. EDIT: Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            abort(403, 'No seller profile found.');
        }

        if ($product->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.'); 
        }

        $categories = Category::where('seller_id', $seller->id)->get();
        
        if ($categories->isEmpty()) {
            return redirect()->route('seller.categories.create')
                ->with('info', 'Please create at least one category first.');
        }
        
        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * 6. UPDATE: Update the specified product in storage
     */
    public function update(Request $request, Product $product)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            abort(403, 'No seller profile found.');
        }

        if ($product->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Store new image
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } else {
            // Keep the existing image if no new image is uploaded
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * 7. DESTROY: Remove the specified product from storage
     */
    public function destroy(Product $product)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return response()->json([
                'success' => false, 
                'message' => 'No seller profile found.'
            ], 403);
        }

        if ($product->seller_id !== $seller->id) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Product deleted successfully!'
        ]);
    }
}