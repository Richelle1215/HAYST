@extends('partials.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart - LUMIÈRE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.header')
    
    <div class="max-w-7xl mx-auto px-4 py-8 mt-20">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Your cart is empty</h2>
                <p class="text-gray-500 mb-8">Add some beautiful jewelry pieces to get started!</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    Continue Shopping
                </a>
            </div>
        @else
            {{-- Cart Items Grouped by Shop --}}
            <div class="space-y-6">
                @foreach($groupedCart as $shop_name => $shopItems)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden shop-group" data-shop="{{ $shop_name }}">
                        {{-- Shop Header --}}
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="shop-checkbox w-5 h-5 rounded text-white border-2 border-white" data-shop="{{ $shop_name }}" onchange="toggleShopItems('{{ addslashes($shop_name) }}')">
                                <div class="flex items-center gap-2 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-xl font-bold">{{ $shop_name }}</span>
                                </div>
                            </div>
                            <a href="#" class="text-white hover:text-gray-200 text-sm flex items-center gap-1">
                                Visit Shop
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        {{-- Products Header --}}
                        <div class="bg-gray-50 px-6 py-3 border-b">
                            <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600">
                                <div class="col-span-1"></div>
                                <div class="col-span-5">Product</div>
                                <div class="col-span-2 text-center">Unit Price</div>
                                <div class="col-span-2 text-center">Quantity</div>
                                <div class="col-span-1 text-center">Total Price</div>
                                <div class="col-span-1 text-center">Actions</div>
                            </div>
                        </div>

                        {{-- Shop Products --}}
                        <div class="divide-y">
                            @foreach($shopItems as $id => $item)
                                <div class="px-6 py-4 cart-item" data-id="{{ $id }}" data-price="{{ $item['price'] }}" data-shop="{{ $shop_name }}">
                                    <div class="grid grid-cols-12 gap-4 items-center">
                                        {{-- Checkbox --}}
                                        <div class="col-span-1">
                                            <input type="checkbox" class="item-checkbox rounded w-4 h-4" data-id="{{ $id }}" data-shop="{{ $shop_name }}" onclick="updateTotal()">
                                        </div>

                                        {{-- Product Info --}}
                                        <div class="col-span-5 flex items-center gap-4">
                                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $item['name'] }}</h3>
                                                <p class="text-sm text-gray-500">{{ $item['category'] ?? 'N/A' }}</p>
                                                {{-- ✅ Display Shop Name in Product Info --}}
                                                <p class="text-xs text-indigo-600 font-medium mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    {{ $item['shop_name'] ?? 'Unknown Shop' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">Stock: {{ $item['stock'] }}</p>
                                            </div>
                                        </div>

                                        {{-- Unit Price --}}
                                        <div class="col-span-2 text-center">
                                            <span class="text-gray-900 font-medium">₱{{ number_format($item['price'], 2) }}</span>
                                        </div>

                                        {{-- Quantity --}}
                                        <div class="col-span-2 flex items-center justify-center gap-2">
                                            <button onclick="updateQuantity({{ $id }}, -1, {{ $item['stock'] }})" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" 
                                                   id="qty-{{ $id }}"
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   max="{{ $item['stock'] }}"
                                                   readonly
                                                   class="w-16 text-center border border-gray-300 rounded py-1 focus:outline-none">
                                            <button onclick="updateQuantity({{ $id }}, 1, {{ $item['stock'] }})" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Total Price --}}
                                        <div class="col-span-1 text-center">
                                            <span class="text-orange-600 font-bold item-total" data-id="{{ $id }}">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="col-span-1 text-center">
                                            <button onclick="removeFromCart({{ $id }})" class="text-red-500 hover:text-red-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Shop Subtotal --}}
                        <div class="bg-gray-50 px-6 py-3 border-t">
                            <div class="flex justify-end items-center gap-4">
                                <span class="text-gray-600">Shop Subtotal:</span>
                                <span class="text-lg font-bold text-orange-600 shop-subtotal" data-shop="{{ $shop_name }}">₱0.00</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Cart Footer with Total --}}
            <div class="bg-white shadow-lg rounded-lg mt-6">
                <div class="px-6 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <input type="checkbox" id="select-all" class="rounded w-5 h-5" onclick="toggleSelectAll()">
                            <label for="select-all" class="text-sm font-semibold">Select All (<span id="item-count">{{ count($cart) }}</span> items)</label>
                            <button onclick="deleteSelected()" class="text-sm text-red-600 hover:text-red-700 font-semibold">Delete Selected</button>
                        </div>
                        
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-1">Total (<span id="selected-count">0</span> item selected):</p>
                                <p class="text-3xl font-bold text-orange-600">₱<span id="grand-total">0.00</span></p>
                            </div>
                            <button onclick="proceedToCheckout()" class="bg-orange-600 text-white px-16 py-4 rounded-lg hover:bg-orange-700 transition font-bold text-lg shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed" id="checkout-btn" disabled>
                                Check Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>

    <script>
        function toggleShopItems(shop_name) {
            const shopCheckbox = document.querySelector(`.shop-checkbox[data-shop="${shop_name}"]`);
            const itemCheckboxes = document.querySelectorAll(`.item-checkbox[data-shop="${shop_name}"]`);

            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = shopCheckbox.checked;
            });
            
            updateTotal();
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all').checked;
            const allCheckboxes = document.querySelectorAll('.item-checkbox, .shop-checkbox');
            
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll;
            });
            
            updateTotal();
        }

        function updateTotal() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            let total = 0;
            let count = 0;
            
            // Calculate shop subtotals
            const shopSubtotals = {};
            
            checkboxes.forEach(checkbox => {
                const id = checkbox.dataset.id;
                const shop = checkbox.dataset.shop;
                const itemTotal = document.querySelector(`.item-total[data-id="${id}"]`);
                const price = parseFloat(itemTotal.textContent.replace('₱', '').replace(',', ''));
                
                total += price;
                count++;
                
                if (!shopSubtotals[shop]) {
                    shopSubtotals[shop] = 0;
                }
                shopSubtotals[shop] += price;
            });
            
            // Update shop subtotals
            document.querySelectorAll('.shop-subtotal').forEach(element => {
                const shop = element.dataset.shop;
                const subtotal = shopSubtotals[shop] || 0;
                element.textContent = '₱' + subtotal.toFixed(2);
            });
            
            document.getElementById('grand-total').textContent = total.toFixed(2);
            document.getElementById('selected-count').textContent = count;
            document.getElementById('checkout-btn').disabled = count === 0;
            
            // Update shop checkboxes based on items
            document.querySelectorAll('.shop-group').forEach(group => {
                const shopName = group.dataset.shop;
                const shopCheckbox = group.querySelector('.shop-checkbox');
                const itemCheckboxes = group.querySelectorAll('.item-checkbox');
                const checkedItems = group.querySelectorAll('.item-checkbox:checked');
                
                shopCheckbox.checked = itemCheckboxes.length > 0 && itemCheckboxes.length === checkedItems.length;
            });
        }

        function updateQuantity(id, change, maxStock) {
            const input = document.getElementById(`qty-${id}`);
            let newQty = parseInt(input.value) + change;
            
            if (newQty < 1) newQty = 1;
            if (newQty > maxStock) {
                alert('Cannot exceed available stock!');
                return;
            }
            
            input.value = newQty;
            
            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        function removeFromCart(id) {
            if(confirm('Remove this item from cart?')) {
                fetch(`/cart/remove/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    }
                });
            }
        }

        function deleteSelected() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            if(checkboxes.length === 0) {
                alert('Please select items to delete');
                return;
            }
            
            if(confirm(`Delete ${checkboxes.length} selected item(s)?`)) {
                const promises = Array.from(checkboxes).map(checkbox => {
                    return fetch(`/cart/remove/${checkbox.dataset.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                });
                
                Promise.all(promises).then(() => location.reload());
            }
        }

        function proceedToCheckout() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            if(checkboxes.length === 0) {
                alert('Please select items to checkout');
                return;
            }
            
            const selectedIds = Array.from(checkboxes).map(cb => cb.dataset.id);
            sessionStorage.setItem('checkoutItems', JSON.stringify(selectedIds));
            window.location.href = '{{ route("cart.checkout") }}';
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            updateTotal();
        });
    </script>
</body>
</html>