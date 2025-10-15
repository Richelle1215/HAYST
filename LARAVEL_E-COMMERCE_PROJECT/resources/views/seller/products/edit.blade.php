@extends('seller.layout')

@section('content')
<div class="bg-white shadow-lg rounded-xl max-w-6xl mx-auto p-10 flex items-start justify-center"
     style="min-height: 540px;">

    {{-- ✅ Left: Product Image --}}
    <div class="w-1/4 flex flex-col items-center">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Current Image</h2>

        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 style="width: 200px; height: 200px; object-fit: cover;"
                 class="rounded-lg shadow-md mb-4">
        @else
            <div style="width: 200px; height: 200px;"
                 class="flex items-center justify-center bg-gray-100 text-gray-500 rounded-lg mb-4">
                No Image
            </div>
        @endif

        <label class="text-sm text-gray-600 mb-1">Change Image</label>
        <input type="file" name="image"
               class="w-52 text-sm border border-gray-200 rounded-md px-3 py-2 focus:ring-1 focus:ring-blue-300">
    </div>

    {{-- ✅ Right: Form Section --}}
    <div class="w-3/4 pl-12">
        <h1 class="text-2xl font-semibold text-gray-800 mb-8 text-center">✏️ Edit Product</h1>

        <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5 w-full pr-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ $product->name }}"
                           class="w-full border border-gray-200 rounded-md px-4 py-3 text-base focus:ring-1 focus:ring-blue-300" required>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-md px-4 py-3 text-base focus:ring-1 focus:ring-blue-300" required>{{ $product->description }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Price</label>
                        <input type="number" name="price" step="0.01" value="{{ $product->price }}"
                               class="w-full border border-gray-200 rounded-md px-4 py-3 text-base focus:ring-1 focus:ring-blue-300" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Stock</label>
                        <input type="number" name="stock" value="{{ $product->stock }}"
                               class="w-full border border-gray-200 rounded-md px-4 py-3 text-base focus:ring-1 focus:ring-blue-300" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-2">Category</label>
                    <select name="category_id"
                            class="w-full border border-gray-200 rounded-md px-4 py-3 text-base focus:ring-1 focus:ring-blue-300" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ✅ Buttons --}}
            <div class="flex justify-end space-x-4 mt-8 pr-6">
                <a href="{{ route('seller.products.index') }}"
                   class="bg-gray-100 text-gray-700 text-sm px-6 py-2.5 rounded-md border border-gray-300 hover:bg-gray-200">
                   Cancel
                </a>
                <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-black text-sm px-6 py-2.5 rounded-md shadow-md">
                    Update Product
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
