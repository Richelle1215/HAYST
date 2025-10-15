@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-8">


    <main class="flex-1 p-8 overflow-y-auto"> {{-- CRITICAL: Ito ang nagpapa-scroll sa content lang --}}
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Category</h1>

            <form action="{{ route('seller.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Category Name</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="w-full border-gray-300 rounded-lg shadow-sm p-2" required> {{-- FIXED: Added p-2 for design --}}
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm p-2" required>{{ $category->description }}</textarea> {{-- FIXED: Added p-2 for design --}}
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('seller.categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">Update Category</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection