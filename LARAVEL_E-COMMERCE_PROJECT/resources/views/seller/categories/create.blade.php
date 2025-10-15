@extends('seller.layout')

@section('content')
<div class="min-h-screen flex items-start justify-center bg-gray-100 p-6 pt-12">
    <div class="w-full max-w-screen-2xl bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">➕ Add New Category</h1>

        <form action="{{ route('seller.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 mb-2 font-medium">Category Name</label>
                <input type="text" name="name" 
                       class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-green-500 focus:outline-none" 
                       required>
            </div>

            <div>
                <label class="block text-gray-700 mb-2 font-medium">Description</label>
                <textarea name="description" rows="4" 
                          class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-green-500 focus:outline-none" 
                          required></textarea>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('seller.categories.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Cancel</a>
                <button type="submit" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Save Category</button>
            </div>
        </form>
    </div>
</div>
@endsection
