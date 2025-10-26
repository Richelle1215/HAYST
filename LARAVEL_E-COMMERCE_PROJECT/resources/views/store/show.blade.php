<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Detail</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Success Message Toast -->
    <div id="successToast" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 transform transition-all duration-300">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div>
                <p class="font-bold">Success!</p>
                <p id="successMessage">Product added to cart</p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:underline mb-4 inline-block font-semibold">&larr; Back to Products</a>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white shadow-2xl rounded-2xl p-8">
            
            <!-- Product Image -->
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="w-full h-[500px] object-cover rounded-xl shadow-lg" 
                         alt="{{ $product->name }}">
                @else
                    <div class="w-full h-[500px] bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-500 text-xl rounded-xl shadow-lg">
                        <div class="text-center">
                            <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>No Image Available</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-3">{{ $product->name }}</h1>
                    <p class="text-3xl text-indigo-600 font-bold mb-4">₱{{ number_format($product->price, 2) }}</p>
                    
                    <div class="space-y-2 text-sm text-gray-600 mb-6">
                        <p class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">Category:</span> 
                            <a href="#" class="text-indigo-600 hover:underline">{{ $product->category->name ?? 'N/A' }}</a>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">Shop:</span> 
                            <span class="text-gray-700">{{ $product->shop_name }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">Stock:</span> 
                            <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of Stock' }}
                            </span>
                        </p>
                    </div>

                    <hr class="my-6 border-gray-200">
                    
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Product Description</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap mb-6">{{ $product->description ?: 'No description available.' }}</p>
                </div>

                <!-- Add to Cart Section -->
                <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                    @auth
                        @if($product->stock > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Quantity</label>
                                <div class="flex items-center gap-3">
                                    <button onclick="decreaseQuantity()" 
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-lg transition">
                                        −
                                    </button>
                                    <input type="number" 
                                           id="quantityInput" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->stock }}" 
                                           class="w-20 text-center px-4 py-2 border-2 border-gray-300 rounded-lg font-bold text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <button onclick="increaseQuantity()" 
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-lg transition">
                                        +
                                    </button>
                                    <span class="text-sm text-gray-500 ml-2">Max: {{ $product->stock }}</span>
                                </div>
                            </div>
                            
                            <button onclick="addToCart()" 
                                    id="addToCartBtn"
                                    class="w-full px-6 py-4 bg-indigo-600 text-white text-lg font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Add to Cart
                            </button>
                            
                            <a href="{{ route('customer.cart.index') }}" 
                               class="block text-center mt-3 text-indigo-600 hover:text-indigo-800 font-semibold underline">
                                View My Cart
                            </a>
                        @else
                            <button disabled 
                                    class="w-full px-6 py-4 bg-gray-400 text-white text-lg font-bold rounded-xl cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Please login to add items to cart</p>
                            <a href="{{ route('login') }}" 
                               class="inline-block px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
                                Login Now
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script>
        const maxStock = {{ $product->stock }};
        const productId = {{ $product->id }};
        const csrfToken = '{{ csrf_token() }}';

        function decreaseQuantity() {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        function increaseQuantity() {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        }

        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('successToast');
            const messageEl = document.getElementById('successMessage');
            
            messageEl.textContent = message;
            
            if (!isSuccess) {
                toast.classList.remove('bg-green-500');
                toast.classList.add('bg-red-500');
            } else {
                toast.classList.remove('bg-red-500');
                toast.classList.add('bg-green-500');
            }
            
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        function addToCart() {
            const quantity = parseInt(document.getElementById('quantityInput').value);
            const button = document.getElementById('addToCartBtn');
            
            // Disable button during request
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-6 w-6 mr-3 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Adding...';
            
            fetch('{{ route("customer.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, true);
                    // Reset quantity to 1
                    document.getElementById('quantityInput').value = 1;
                } else {
                    showToast(data.message, false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', false);
            })
            .finally(() => {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Add to Cart';
            });
        }

        // Prevent typing values outside range
        document.getElementById('quantityInput').addEventListener('input', function() {
            if (this.value > maxStock) this.value = maxStock;
            if (this.value < 1) this.value = 1;
        });
    </script>

</body>
</html>