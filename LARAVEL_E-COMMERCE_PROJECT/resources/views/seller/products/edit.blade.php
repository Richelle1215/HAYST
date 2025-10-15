<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 

                    <div class="col-span-6 sm:col-span-4 mb-4">
                        <x-label for="name" value="{{ __('Product Name') }}" />
                        <x-input id="name" type="text" name="name" class="mt-1 block w-full" value="{{ old('name', $product->name) }}" required />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-4">
                        <x-label for="category_id" value="{{ __('Category') }}" />
                        <select id="category_id" name="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="category_id" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-label for="price" value="{{ __('Price (P)') }}" />
                            <x-input id="price" type="number" step="0.01" name="price" class="mt-1 block w-full" value="{{ old('price', $product->price) }}" required />
                            <x-input-error for="price" class="mt-2" />
                        </div>
                        <div>
                            <x-label for="stock" value="{{ __('Stock') }}" />
                            <x-input id="stock" type="number" name="stock" class="mt-1 block w-full" value="{{ old('stock', $product->stock) }}" required />
                            <x-input-error for="stock" class="mt-2" />
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-4">
                        <x-label for="description" value="{{ __('Description') }}" />
                        <textarea id="description" name="description" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>{{ old('description', $product->description) }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-6">
                        <x-label for="image" value="{{ __('Current Image') }}" />
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-24 h-24 object-cover mb-2 rounded" alt="Current Product Image">
                        @else
                            <p class="mb-2 text-sm text-gray-500">No Image Uploaded</p>
                        @endif
                        <x-label for="image" value="{{ __('Upload New Image (Optional)') }}" />
                        <x-input id="image" type="file" name="image" class="mt-1 block w-full p-2 border" />
                        <x-input-error for="image" class="mt-2" />
                    </div>

                    <x-button class="mt-4">
                        {{ __('Update Product') }}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>