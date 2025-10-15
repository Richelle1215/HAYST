@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-4xl mx-auto">

    <h1 class="text-xl font-semibold text-gray-800 mb-4">✏️ Edit Product</h1>

    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="block text-gray-700 mb-1 text-sm">Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}" 
                   class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-gray-200" required>
        </div>

        <div class="mb-3">
            <label class="block text-gray-700 mb-1 text-sm">Description</label>
            <textarea name="description" rows="4" 
                      class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-gray-200" required>{{ $product->description }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
                <label class="block text-gray-700 mb-1 text-sm">Price</label>
                <input type="number" name="price" step="0.01" value="{{ $product->price }}" 
                       class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-gray-200" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1 text-sm">Stock</label>
                <input type="number" name="stock" value="{{ $product->stock }}" 
                       class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-gray-200" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-gray-700 mb-1 text-sm">Category</label>
            <select name="category_id" 
                    class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-gray-200" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1 text-sm">Current Image</label>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" class="h-24 rounded mb-3 border">
            @else
                <p class="text-gray-500 italic text-sm">No image uploaded.</p>
            @endif

            <label class="block text-gray-700 mb-1 text-sm">Change Image</label>
            <input type="file" name="image" 
                   class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-gray-200">
        </div>

        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ route('seller.products.index') }}" 
               class="bg-gray-500 text-white text-sm px-4 py-2 rounded hover:bg-gray-600">
               Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                Update Product
            </button>
        </div>
    </form>

</div>
@endsection
