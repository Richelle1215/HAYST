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
    // 1. INDEX: Show list of products
    public function index()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to view products.');
        }

        $products = Product::where('seller_id', $seller->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    // 2. CREATE: Show form to create new product
    public function create()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to create products.');
        }
        
        $categories = Category::where('seller_id', $seller->id)->get(); 
        
        return view('seller.products.create', compact('categories')); 
    }

    // 3. STORE: Save new product
    public function store(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to create products.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0', 
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $validated['seller_id'] = $seller->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = null;
        }

        Product::create($validated);
        
        return redirect()->route('seller.products.index')
                         ->with('success', 'Product saved successfully.');
    }

    // 4. SHOW: Display single product
    public function show(Product $product)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller || $product->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.products.show', compact('product'));
    }

    // 5. EDIT: Show form to edit product
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
        
        return view('seller.products.edit', compact('product', 'categories'));
    }

    // 6. UPDATE: Update product
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
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } else {
            // Keep the existing image if no new image is uploaded
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    // 7. DESTROY: Delete product
    public function destroy(Product $product)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return response()->json(['success' => false, 'message' => 'No seller profile found.'], 403);
        }

        if ($product->seller_id !== $seller->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }
}