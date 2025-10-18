<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. INDEX: Ipakita ang listahan ng Categories
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        // Tiyakin na mayroon kang view: resources/views/seller/categories/index.blade.php
        return view('seller.categories.index', compact('categories'));
    }

    // 2. CREATE: Ipakita ang form para sa bagong Category
    public function create()
    {
        // Tiyakin na mayroon kang view: resources/views/seller/categories/create.blade.php
        return view('seller.categories.create');
    }

    // 3. STORE: I-save ang bagong Category
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:categories|max:255',
        'description' => 'nullable',
    ]);

    Category::create([
        'name' => $request->name,
        'description' => $request->description,
        'seller_id' => auth()->id(),
    ]);

    return redirect()->route('seller.categories.index')
                     ->with('success', 'Category added successfully!');
}




    // 4. SHOW: Ipakita ang detalye (Hindi karaniwan sa Categories, pwedeng i-redirect na lang)
    public function show(Category $category)
    {
        // Pwede mong i-redirect sa product listing na naka-filter
        return redirect()->route('products.filter', $category);
    }

    // 5. EDIT: Ipakita ang form para i-edit ang Category
    public function edit(Category $category)
    {
        // Tiyakin na mayroon kang view: resources/views/seller/categories/edit.blade.php
        return view('seller.categories.edit', compact('category'));
    }

    // 6. UPDATE: I-update ang Category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    // 7. DESTROY: Burahin/I-delete ang Category
        public function destroy(Category $category)
        {
            $category->delete();

            return response()->json(['success' => true]);
        }

}