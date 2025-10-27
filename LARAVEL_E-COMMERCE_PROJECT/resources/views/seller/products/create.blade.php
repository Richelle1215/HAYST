@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Add Product</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent-color: #212158ff;
            --light-bg: #f5f5f5;
            --primary-text: #222222;
            --secondary-text: #666666;
        }

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--primary-text);
        }

        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }

        .text-accent { color: var(--accent-color); }
        .bg-accent { background-color: var(--accent-color); }
        .hover\:bg-accent-dark:hover { background-color: #121338ff; }
    </style>
</head>

<body class="antialiased">

    <div class="max-w-4xl mx-auto px-8 py-12">
        {{-- HEADER --}}
        <div class="text-center mb-10">
            <h1 class="font-serif-elegant text-4xl font-bold text-gray-900 mb-2">+ Add New Product</h1>
            <p class="text-gray-500 text-sm">Showcase your latest collection with style</p>
        </div>

        {{-- FORM CARD --}}
        
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- PRODUCT NAME --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Product Name</label>
                    <input type="text" name="name"
                           class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-accent focus:border-accent"
                           placeholder="Enter product name" required>
                </div>

                {{-- DESCRIPTION --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-accent focus:border-accent"
                              placeholder="Enter product description" required></textarea>
                </div>

                {{-- PRICE & STOCK --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Price (₱)</label>
                        <input type="number" name="price" step="0.01"
                               class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-accent focus:border-accent"
                               placeholder="0.00" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Stock</label>
                        <input type="number" name="stock"
                               class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-accent focus:border-accent"
                               placeholder="0" required>
                    </div>
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Category</label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2.5 bg-white focus:ring-2 focus:ring-accent focus:border-accent"
                            required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- IMAGE --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Product Image</label>
                    <input type="file" name="image"
                           class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2.5 bg-gray-50 focus:ring-2 focus:ring-accent focus:border-accent">
                    <p class="text-xs text-gray-500 mt-1">Upload a clear photo (JPG, PNG)</p>
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-center space-x-4 pt-6">
                    <a href="{{ route('seller.products.index') }}"
                       class="px-6 py-2.5 rounded-lg shadow-md text-white transition-all duration-300 bg-gray-400 hover:bg-gray-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg shadow-md text-white transition-all duration-300 bg-accent hover:bg-accent-dark transform hover:scale-105">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
@endsection
