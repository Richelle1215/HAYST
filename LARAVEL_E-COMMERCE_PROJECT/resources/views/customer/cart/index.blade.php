@extends('customer.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMIÈRE | My Cart</title>

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

    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">My Shopping Cart</h1>
                <p class="text-gray-500 text-sm mt-1">Review your selected items before checkout</p>
            </div>
        </div>

        {{-- EMPTY CART --}}
        @if($cartItems->isEmpty())
            <div class="bg-white shadow-xl rounded-xl p-12 text-center border border-gray-100">
                <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 
                             0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="font-serif-elegant text-2xl text-gray-700 mb-2">Your Cart is Empty</h2>
                <p class="text-gray-500 mb-6">Start shopping and add items to your collection.</p>
                <a href="{{ route('store.index') }}" 
                   class="bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- CART ITEMS --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300" id="cart-item-{{ $item->id }}">
                            <div class="flex gap-6">
                                {{-- PRODUCT IMAGE --}}
                                <div class="w-32 h-32 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">No image</div>
                                    @endif
                                </div>

                                {{-- PRODUCT DETAILS --}}
                                <div class="flex-1">
                                    <h3 class="text-xl font-serif-elegant font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500">Shop: <span class="font-semibold">{{ $item->product->shop_name }}</span></p>
                                    

                                    <p class="text-xl font-bold text-accent mt-2">₱{{ number_format($item->product->price, 2) }}</p>

                                    {{-- QUANTITY CONTROL --}}
                                    <div class="flex items-center gap-3 mt-4">
                                        <label class="text-sm font-semibold text-gray-700">Qty:</label>
                                        <div class="flex items-center gap-2">
                                            <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }}, {{ $item->product->stock }})" 
                                                    class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                                            
                                            <input type="number" 
                                                   id="qty-{{ $item->id }}" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}"
                                                   onchange="updateQuantity({{ $item->id }}, this.value, {{ $item->product->stock }})"
                                                   class="w-16 text-center px-2 py-1 border-2 border-gray-300 rounded font-semibold focus:ring-2 focus:ring-indigo-500">

                                            <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }}, {{ $item->product->stock }})"
                                                    class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold"
                                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                                        </div>
                                        <span class="text-xs text-gray-500">(Max: {{ $item->product->stock }})</span>
                                    </div>

                                    {{-- SUBTOTAL --}}
                                    <p class="text-sm text-gray-600 mt-3">
                                        Subtotal: <span class="font-bold text-gray-900" id="subtotal-{{ $item->id }}">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </p>
                                </div>

                                {{-- REMOVE BUTTON --}}
                                <button onclick="removeFromCart({{ $item->id }})" 
                                        class="text-red-500 hover:text-red-700 transition self-start">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 
                                                 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 
                                                 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ORDER SUMMARY --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-xl p-8 sticky top-24">
                        <h2 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold" id="cart-subtotal">₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="font-semibold">FREE</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-xl font-bold text-gray-900">
                                <span>Total</span>
                                <span id="cart-total">₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('customer.checkout.index') }}" 
                           class="block w-full text-center bg-accent hover:bg-accent-dark text-white py-4 rounded-lg font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-md mb-4">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('store.index') }}" 
                           class="block text-center text-accent hover:text-indigo-800 font-semibold">
                            ← Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- JS --}}
    <script>
        const csrfToken = '{{ csrf_token() }}';

        function updateQuantity(itemId, newQuantity, maxStock) {
            newQuantity = parseInt(newQuantity);
            if (newQuantity < 1 || newQuantity > maxStock) {
                alert(`Quantity must be between 1 and ${maxStock}`);
                return;
            }
            fetch(`/customer/cart/update/${itemId}`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(r => r.json())
            .then(d => { if (d.success) location.reload(); else alert(d.message); });
        }

        function removeFromCart(itemId) {
            if (!confirm('Remove this item?')) return;
            fetch(`/customer/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': csrfToken}
            })
            .then(r => r.json())
            .then(d => { if (d.success) location.reload(); else alert('Error removing item'); });
        }
    </script>

</body>
</html>
@endsection
