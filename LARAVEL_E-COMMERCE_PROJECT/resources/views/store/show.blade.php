<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} Detail</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto px-4 py-8">
        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline mb-4 inline-block">&larr; Back</a>
        
        {{-- Success Message --}}
        @if(session('success'))
        <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline" id="success-text">{{ session('success') }}</span>
        </div>
        @else
        <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline" id="success-text"></span>
        </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
        <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline" id="error-text">{{ session('error') }}</span>
        </div>
        @else
        <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline" id="error-text"></span>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white shadow-xl rounded-lg p-6">
            
            <div>
                <div class="w-full h-96 rounded-lg overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="w-full h-full object-cover rounded-lg" 
                             alt="{{ $product->name }}">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-xl rounded-lg">No Image Available</div>
                    @endif
                </div>
            </div>

            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">{{ $product->name }}</h1>
                <p class="text-2xl text-indigo-600 font-bold mt-2">₱{{ number_format($product->price, 2) }}</p>
                
                <div class="mt-4 space-y-2 text-sm text-gray-600">
                    {{-- Shop Name Display --}}
                    <p class="flex items-center gap-2">
                        <strong>Shop:</strong> 
                        <span class="inline-flex items-center gap-1 text-indigo-600 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $product->seller ? $product->seller->shop_name : ($product->shop_name ?? 'LUMIÈRE Main Store') }}
                        </span>
                    </p>
                    
                    <p><strong>Category:</strong> 
                        <span class="text-indigo-500">{{ $product->category->name }}</span>
                    </p>
                    
                    <p><strong>Stock:</strong> 
                        <span class="{{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }} font-semibold">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}
                        </span>
                    </p>
                </div>

                <hr class="my-6">
                
                <h2 class="text-2xl font-semibold text-gray-800">Product Description</h2>
                <p class="text-gray-700 mt-2 whitespace-pre-wrap">{{ $product->description }}</p>

                @if($product->stock > 0)
                    <div class="mt-6 flex items-center gap-4">
                        <label for="quantity" class="text-gray-700 font-semibold">Quantity:</label>
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button onclick="decreaseQuantity()" class="px-4 py-2 hover:bg-gray-100">-</button>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}"
                                   readonly
                                   class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none">
                            <button onclick="increaseQuantity({{ $product->stock }})" class="px-4 py-2 hover:bg-gray-100">+</button>
                        </div>
                    </div>

                    <button onclick="addToCart({{ $product->id }})" 
                            id="add-to-cart-btn"
                            class="mt-6 px-8 py-3 bg-indigo-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <span id="btn-text">Add to Cart</span>
                        <span id="btn-loading" class="hidden">
                            <svg class="animate-spin inline-block h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Adding...
                        </span>
                    </button>

                    <a href="{{ route('cart.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline font-semibold">
                        View Cart →
                    </a>
                @else
                    <button disabled class="mt-8 px-8 py-3 bg-gray-400 text-white text-lg font-semibold rounded-lg shadow-md cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        function increaseQuantity(max) {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        function addToCart(productId) {
            const btn = document.getElementById('add-to-cart-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const quantity = parseInt(document.getElementById('quantity').value);
            
            // Disable button and show loading
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    quantity: quantity 
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                if(data.success) {
                    showMessage('success', data.message || 'Product added to cart successfully!');
                    // Optional: Update cart count in header if you have one
                    if(data.cart_count) {
                        updateCartCount(data.cart_count);
                    }
                } else {
                    showMessage('error', data.message || 'Failed to add product to cart.');
                }
            })
            .catch(error => {
                showMessage('error', error.message || 'An error occurred. Please try again.');
                console.error('Error:', error);
            })
            .finally(() => {
                // Re-enable button and hide loading
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            });
        }
        
        function showMessage(type, message) {
            const successDiv = document.getElementById('success-message');
            const errorDiv = document.getElementById('error-message');
            
            if(type === 'success') {
                document.getElementById('success-text').textContent = message;
                successDiv.classList.remove('hidden');
                errorDiv.classList.add('hidden');
                
                setTimeout(() => {
                    successDiv.classList.add('hidden');
                }, 5000);
            } else {
                document.getElementById('error-text').textContent = message;
                errorDiv.classList.remove('hidden');
                successDiv.classList.add('hidden');
                
                setTimeout(() => {
                    errorDiv.classList.add('hidden');
                }, 5000);
            }
            
            // Scroll to top to show message
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if(cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.classList.remove('hidden');
            }
        }

        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            
            if(successMsg && !successMsg.classList.contains('hidden')) {
                setTimeout(() => {
                    successMsg.classList.add('hidden');
                }, 5000);
            }
            
            if(errorMsg && !errorMsg.classList.contains('hidden')) {
                setTimeout(() => {
                    errorMsg.classList.add('hidden');
                }, 5000);
            }
        });
    </script>
</body>
</html>