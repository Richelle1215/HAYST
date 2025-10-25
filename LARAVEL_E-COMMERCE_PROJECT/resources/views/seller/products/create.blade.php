
@extends('seller.layout')

@section('content')


    <main class="flex-1 p-8 overflow-y-auto">
        
            <h1 class="text-2xl font-bold text-gray-800 mb-6">+ Add New Product</h1>

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="name" class="w-90 border-gray-300 rounded-lg shadow-sm p-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm p-2" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Price</label>
                        <input type="number" name="price" step="0.01" class="w-full border-gray-300 rounded-lg shadow-sm p-2" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Stock</label>
                        <input type="number" name="stock" class="w-full border-gray-300 rounded-lg shadow-sm p-2" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm p-2" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Product Image</label>
                    <input type="file" name="image" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('seller.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="bg-gray-500 text-white  px-4 py-2 rounded hover:bg-green-700">Save Product</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection