<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $product->name }} Detail</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    {{-- Tandaan: Pinalitan ko ang 'w-200px h-200px' ng standard Tailwind class 'max-w-3xl' --}}
    <div class="max-w-3xl mx-auto px-4 py-8"> 
        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:underline mb-4 inline-block">&larr; Back</a>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white shadow-xl rounded-lg p-6">
            
            {{-- Dito nakalagay ang container ng larawan --}}
            <div>
                {{-- FIX: Nagdagdag ng fixed height (h-96) at width (w-full) sa container ng larawan --}}
                <div class="w-full h-96 rounded-lg overflow-hidden"> 
                    @if($product->image)
                        {{-- FIX: Ginawa nating 'h-full w-full' ang size at 'object-cover' ang fit --}}
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="w-full h-full object-cover rounded-lg" 
                             alt="{{ $product->name }}">
                    @else
                        {{-- Binago din ang 'No Image Available' container para maging consistent --}}
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-xl rounded-lg">No Image Available</div>
                    @endif
                </div>
            </div>

            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">{{ $product->name }}</h1>
                <p class="text-2xl text-indigo-600 font-bold mt-2">P{{ number_format($product->price, 2) }}</p>
                
                <div class="mt-4 text-sm text-gray-600">
                    <p><strong>Category:</strong> <a href="{{ route('products.filter', $product->category) }}" class="text-indigo-500 hover:underline">{{ $product->category->name }}</a></p>
                    <p><strong>Stock:</strong> 
                        <span class="{{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }} font-semibold">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}
                        </span>
                    </p>
                </div>

                <hr class="my-6">
                
                <h2 class="text-2xl font-semibold text-gray-800">Product Description</h2>
                <p class="text-gray-700 mt-2 whitespace-pre-wrap">{{ $product->description }}</p>

                <button class="mt-8 px-6 py-3 bg-indigo-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition" 
                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </div>
        </div>
    </div>
</body>
</html>