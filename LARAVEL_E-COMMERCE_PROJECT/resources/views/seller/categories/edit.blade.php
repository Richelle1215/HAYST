@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Edit Category</title>

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

    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-10 mt-10 border border-gray-100">

        {{-- HEADER --}}
        <div class="text-center mb-10 border-b border-gray-200 pb-4">
            <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">✏️ Edit Category</h1>
            <p class="text-gray-500 text-sm mt-2">Update your existing category details below</p>
        </div>

        {{-- FORM --}}
        <form action="{{ route('seller.categories.update', $category->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- CATEGORY NAME --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Category Name</label>
                <input type="text" name="name" value="{{ $category->name }}"
                       class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring focus:ring-blue-300 focus:border-blue-400"
                       placeholder="Enter category name" required>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring focus:ring-blue-300 focus:border-blue-400"
                          placeholder="Enter category description" required>{{ $category->description }}</textarea>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-center space-x-4 pt-6">
                <a href="{{ route('seller.categories.index') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg shadow transition-all">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-accent hover:bg-accent-dark text-white px-6 py-2 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                    Update Category
                </button>
            </div>
        </form>
    </div>

</body>
</html>
@endsection
