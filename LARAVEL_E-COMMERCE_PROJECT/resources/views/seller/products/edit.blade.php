@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>LUMIÈRE | Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --accent-color: #212158ff;
            --accent-hover: #121338ff;
            --light-bg: #fafafa;
        }

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
        }

        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }

        .btn-accent {
            background-color: var(--accent-color);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-accent:hover {
            background-color: var(--accent-hover);
            transform: scale(1.03);
        }

        .input-style {
            @apply w-full border border-gray-200 rounded-lg px-4 py-3 text-base focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300;
        }
    </style>
</head>

<body class="antialiased">


        {{-- HEADER --}}
        <div class="mb-10 border-b border-gray-200 pb-5 text-center">
            <h1 class="font-serif-elegant text-4xl font-bold text-gray-900"> Edit Product</h1>
            <p class="text-gray-500 text-sm mt-2">Update your product details to keep your catalog current</p>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('seller.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-col md:flex-row items-start justify-between space-x-0 md:space-x-10">
                {{-- ✅ Left: Product Image --}}
                <div class="w-full md:w-1/3 flex flex-col items-center">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Current Image</h2>

                    @if($product->image)
                        <?php
                            $imagePath = 'storage/' . $product->image;
                            $fullStoragePath = storage_path('app/public/' . $product->image);
                            $v = file_exists($fullStoragePath) ? filemtime($fullStoragePath) : time();
                        ?>
                        <img src="{{ asset($imagePath) }}?v={{ $v }}"
                             alt="{{ $product->name }}"
                             class="w-56 h-56 object-cover rounded-xl shadow-md border mb-4">
                    @else
                        <div class="w-56 h-56 bg-gray-100 rounded-xl mb-4 flex items-center justify-center text-gray-500 border">
                            No image
                        </div>
                    @endif

                    <label class="text-sm text-gray-600 mb-1">Change Image</label>
                    <input type="file" name="image"
                           class="w-60 text-sm border border-gray-300 rounded-md px-3 py-2 bg-gray-50 focus:ring-1 focus:ring-indigo-300">
                </div>

                {{-- ✅ Right: Product Form --}}
                <div class="w-full md:w-2/3 mt-8 md:mt-0">
                    <div class="space-y-6">

                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Product Name</label>
                            <input type="text" name="name" value="{{ $product->name }}" class="input-style" required>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Description</label>
                            <textarea name="description" rows="4" class="input-style" required>{{ $product->description }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Price</label>
                                <input type="number" name="price" step="0.01" value="{{ $product->price }}" class="input-style" required>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Stock</label>
                                <input type="number" name="stock" value="{{ $product->stock }}" class="input-style" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Category</label>
                            <select name="category_id" class="input-style" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ✅ Buttons --}}
                    <div class="flex justify-end space-x-4 mt-10">
                        <a href="{{ route('seller.products.index') }}"
                           class="bg-gray-100 text-gray-700 text-sm px-6 py-2.5 rounded-md border border-gray-300 hover:bg-gray-200 transition-all duration-200">
                           Cancel
                        </a>

                        <button type="submit"
                                class="btn-accent px-8 py-2.5 rounded-md shadow-md text-sm font-medium">
                         Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
@endsection
