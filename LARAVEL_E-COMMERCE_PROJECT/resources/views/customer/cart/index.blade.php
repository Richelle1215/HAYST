
@extends('customer.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - LUMIÈRE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">


    <div class="container mx-auto px-4 py-12 mt-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">My Shopping Cart</h1>

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-700 mb-4">Your cart is empty</h2>
                <p class="text-gray-500 mb-6">Start shopping and add items to your cart!</p>
                <a href="{{ route('store.index') }}" 
                   class="inline-block px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow duration-300" id="cart-item-{{ $item->id }}">
                            <div class="flex gap-6">
                                
                                <!-- Product Image -->
                                <div class="w-32 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">Shop: <span class="font-semibold">{{ $item->product->shop_name }}</span></p>
                                    <p class="text-sm text-gray-500 mb-3">Customer: <span class="font-semibold">{{ $item->customer_name }}</span></p>
                                    <p class="text-2xl font-bold text-indigo-600">₱{{ number_format($item->product->price, 2) }}</p>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center gap-3 mt-4">
                                        <label class="text-sm font-semibold text-gray-700">Qty:</label>
                                        <div class="flex items-center gap-2">
                                            <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }}, {{ $item->product->stock }})" 
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold w-8 h-8 rounded transition"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                −
                                            </button>
                                            <input type="number" 
                                                   id="qty-{{ $item->id }}"
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}"
                                                   onchange="updateQuantity({{ $item->id }}, this.value, {{ $item->product->stock }})"
                                                   class="w-16 text-center px-2 py-1 border-2 border-gray-300 rounded font-bold focus:ring-2 focus:ring-indigo-500">
                                            <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }}, {{ $item->product->stock }})" 
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold w-8 h-8 rounded transition"
                                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                +
                                            </button>
                                        </div>
                                        <span class="text-xs text-gray-500">(Max: {{ $item->product->stock }})</span>
                                    </div>

                                    <!-- Subtotal -->
                                    <p class="text-sm text-gray-600 mt-3">
                                        Subtotal: <span class="font-bold text-gray-900" id="subtotal-{{ $item->id }}">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </p>
                                </div>

                                <!-- Remove Button -->
                                <div>
                                    <button onclick="removeFromCart({{ $item->id }})" 
                                            class="text-red-500 hover:text-red-700 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-2xl p-8 sticky top-24">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                        
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
                           class="block w-full bg-indigo-600 text-white text-center py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg mb-4">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('store.index') }}" 
                           class="block text-center text-indigo-600 hover:text-indigo-800 font-semibold">
                            ← Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>


<script>
    const csrfToken = '{{ csrf_token() }}';

    function updateQuantity(itemId, newQuantity, maxStock) {
        newQuantity = parseInt(newQuantity);
        
        if (newQuantity < 1 || newQuantity > maxStock) {
            alert(`Quantity must be between 1 and ${maxStock}`);
            document.getElementById(`qty-${itemId}`).value = newQuantity < 1 ? 1 : maxStock;
            return;
        }

        fetch(`/customer/cart/update/${itemId}`, {  // ← BINAGO KO ITO
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the subtotal for this item
                const itemPrice = parseFloat(data.item_total);
                document.getElementById(`subtotal-${itemId}`).textContent = 
                    `₱${itemPrice.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                
                // Update cart totals
                document.getElementById('cart-subtotal').textContent = 
                    `₱${parseFloat(data.cart_total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                document.getElementById('cart-total').textContent = 
                    `₱${parseFloat(data.cart_total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                
                // Update input value
                document.getElementById(`qty-${itemId}`).value = newQuantity;
            } else {
                alert(data.message || 'Failed to update quantity');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
            location.reload();
        });
    }

    function removeFromCart(itemId) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
        }

        fetch(`/customer/cart/remove/${itemId}`, {  // ← BINAGO KO ITO
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove the item from DOM
                const itemElement = document.getElementById(`cart-item-${itemId}`);
                if (itemElement) {
                    itemElement.style.transition = 'opacity 0.3s';
                    itemElement.style.opacity = '0';
                    setTimeout(() => {
                        itemElement.remove();
                        
                        // Check if cart is now empty
                        const remainingItems = document.querySelectorAll('[id^="cart-item-"]');
                        if (remainingItems.length === 0) {
                            location.reload(); // Reload to show empty cart message
                        } else {
                            // Update totals
                            document.getElementById('cart-subtotal').textContent = 
                                `₱${parseFloat(data.cart_total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            document.getElementById('cart-total').textContent = 
                                `₱${parseFloat(data.cart_total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        }
                    }, 300);
                }
            } else {
                alert(data.message || 'Failed to remove item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
        });
    }
</script>

</body>
</html>
@endsection