@extends('customer.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMIÈRE | Checkout</title>

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

    <div class="max-w-3xl mx-auto px-6 py-12 mt-10">
        {{-- HEADER --}}
        <div class="text-center mb-10 border-b border-gray-200 pb-4">
            <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-500 mt-2 text-sm">Review your order and enter your shipping details</p>
        </div>

        {{-- SHIPPING FORM --}}
        <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100 mb-10">
            <h2 class="text-2xl font-serif-elegant font-semibold text-gray-800 mb-6">Shipping Information</h2>

            <form action="{{ route('customer.checkout.process') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" 
                        name="full_name"
                        value="{{ auth()->user()->name }}"
                        readonly
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email"
                        name="email"
                        value="{{ auth()->user()->email }}"
                        readonly
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number <span class="text-red-500">*</span></label>
                    <input type="text"
                        name="contact_number"
                        required
                        placeholder="+63 912 345 6789"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address <span class="text-red-500">*</span></label>
                    <textarea name="shipping_address" rows="3" required
                        placeholder="House/Unit No., Street, Barangay, City, Province, Postal Code"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition">
                        <option value="">Select Payment Method</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="gcash">GCash</option>
                        <option value="paymaya">PayMaya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
                    <textarea name="notes" rows="2"
                        placeholder="Any special instructions for your order..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition"></textarea>
                </div>

                <button type="submit" 
                    class="w-full bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-[1.02] shadow-md">
                    Place Order 
                </button>
            </form>
        </div>

        {{-- ORDER SUMMARY AT BOTTOM --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <h2 class="text-2xl font-serif-elegant font-semibold text-gray-800 mb-6">Order Summary</h2>

            <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                @foreach($cartItems as $item)
                    <div class="flex gap-4 pb-4 border-b border-gray-200">
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-sm">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">Shop: {{ $item->product->shop_name }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                <span class="font-bold text-accent">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="border-gray-200 mb-4">

            <div class="space-y-2 mb-4 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span class="font-semibold">₱{{ number_format($total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping Fee</span>
                    <span class="font-semibold text-green-600">FREE</span>
                </div>
            </div>

            <hr class="border-gray-200 mb-4">

            <div class="flex justify-between text-xl font-bold text-gray-800">
                <span>Total</span>
                <span class="text-accent">₱{{ number_format($total, 2) }}</span>
            </div>

            <a href="{{ route('customer.cart.index') }}" 
                class="block text-center mt-6 text-accent hover:text-[#121338ff] font-semibold transition">
                ← Back to Cart
            </a>
        </div>

    </div>
</body>
</html>
@endsection
