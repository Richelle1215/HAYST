<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // 1. INDEX: Ipakita ang listahan ng Categories (FIXED - Only show seller's own categories)
    public function index()
    {
        // Get the seller record for the authenticated user
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to view categories.');
        }
        
        // Get only THIS seller's categories
        $categories = Category::where('seller_id', $seller->id)
            ->latest()
            ->paginate(10);
            
        return view('seller.categories.index', compact('categories'));
    }

    // 2. CREATE: Ipakita ang form para sa bagong Category
    public function create()
    {
        // Check if seller exists
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to create categories.');
        }
        
        return view('seller.categories.create');
    }

    // 3. STORE: I-save ang bagong Category (FIXED - Use seller table ID)
    public function store(Request $request)
    {
        // Get the seller record for the authenticated user
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a seller profile to create categories.');
        }
        
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,NULL,id,seller_id,' . $seller->id,
            'description' => 'nullable',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'seller_id' => $seller->id, // FIXED: Use seller table ID, not user_id
        ]);

        return redirect()->route('seller.categories.index')
                         ->with('success', 'Category added successfully!');
    }

    // 4. SHOW: Ipakita ang detalye
    public function show(Category $category)
    {
        // Security check - make sure category belongs to this seller
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller || $category->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return redirect()->route('products.filter', $category);
    }

    // 5. EDIT: Ipakita ang form para i-edit ang Category (FIXED - Security check)
    public function edit(Category $category)
    {
        // Get the seller record
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            abort(403, 'No seller profile found.');
        }
        
        // Security check - make sure category belongs to this seller
        if ($category->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('seller.categories.edit', compact('category'));
    }

    // 6. UPDATE: I-update ang Category (FIXED - Security check)
    public function update(Request $request, Category $category)
    {
        // Get the seller record
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            abort(403, 'No seller profile found.');
        }
        
        // Security check - make sure category belongs to this seller
        if ($category->seller_id !== $seller->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id . ',id,seller_id,' . $seller->id,
            'description' => 'nullable',
        ]);

        $category->update($request->all());

        return redirect()->route('seller.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    // 7. DESTROY: Burahin/I-delete ang Category (FIXED - Security check)
    public function destroy(Category $category)
    {
        // Get the seller record
        $seller = Seller::where('user_id', Auth::id())->first();
        
        if (!$seller) {
            return response()->json(['success' => false, 'message' => 'No seller profile found.'], 403);
        }
        
        // Security check - make sure category belongs to this seller
        if ($category->seller_id !== $seller->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
        
        $category->delete();

        return response()->json(['success' => true]);
    }
}