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
    public function index()
    {
        // Get the currently logged-in seller
        $seller = Seller::where('user_id', Auth::id())->first();

        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to view products.');
        }

        // Fetch only products that belong to this seller
        $products = Product::where('seller_id', $seller->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    /**
     * 2. CREATE: Ipakita ang form para gumawa ng bagong Product
     */
    public function create()
    {
        // Get the seller record for the authenticated user
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to create products.');
        }
        
        // Get categories that belong to this seller
        $categories = Category::where('seller_id', $seller->id)->get(); 
        
        return view('seller.products.create', compact('categories')); 
    }

    /**
     * 3. STORE: I-save ang bagong Product (FIXED)
     */
    public function store(Request $request)
    {
        // Get the seller record for the authenticated user
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to create products.');
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0', 
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Use the SELLER TABLE ID, not the user_id
        $validated['seller_id'] = $seller->id;

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = null;
        }

        // I-save ang produkto sa database
        Product::create($validated);
        
        // Redirection at Success Message
        return redirect()->route('seller.products.index')
                         ->with('success', 'Product saved successfully.');
    }

    /**
     * 5. EDIT: Ipakita ang form para i-edit ang Product
     */
    public function edit(Product $product)
    {
        // Get the seller record
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            abort(403, 'No seller profile found.');
        }

        // Check if the product belongs to the authenticated seller (Security)
        if ($product->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.'); 
        }

        // Get categories that belong to this seller
        $categories = Category::where('seller_id', $seller->id)->get();
        
        return view('seller.products.edit', compact('product', 'categories'));
    }
    
    // Add your other methods here (show, update, destroy)
}