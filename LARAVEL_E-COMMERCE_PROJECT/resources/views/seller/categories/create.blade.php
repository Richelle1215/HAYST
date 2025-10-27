@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Add Category</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent-color: #212158ff;
            --accent-dark: #121338ff;
            --light-bg: #f5f5f5;
            --primary-text: #222222;
            --secondary-text: #666666;
        }

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600&display=swap');

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
        .hover\:bg-accent-dark:hover { background-color: var(--accent-dark); }
    </style>
</head>

<body class="antialiased">

<div class=" flex justify-center items-center py-12 px-4">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-lg border border-gray-100 p-10">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-serif-elegant font-bold text-gray-900">+ Add New Category</h1>
            <p class="text-gray-500 mt-1">Create a new product category for your shop</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('seller.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Category Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                <input type="text" name="name" 
                       class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-[var(--accent-color)] focus:outline-none transition"
                       placeholder="e.g., Necklaces, Rings, Earrings"
                       required>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-[var(--accent-color)] focus:outline-none resize-none transition"
                          placeholder="Write a short description about this category..."
                          required></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('seller.categories.index') }}" 
                   class="bg-gray-400 text-white px-5 py-2.5 rounded-lg hover:bg-gray-500 transition-all duration-200 shadow-sm">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-accent hover:bg-accent-dark text-white px-6 py-2.5 rounded-lg shadow-md transition-all duration-200 transform hover:scale-[1.02]">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
@endsection
