@extends('customer.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - LUMIÈRE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        
        <h1 class="font-serif-elegant text-4xl font-bold text-gray-900 mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Shipping Information</h2>
                    
                    <form action="{{ route('customer.checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name
                            </label>
                            <input type="text" 
                                   name="full_name" 
                                   value="{{ auth()->user()->name }}" 
                                   readonly
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ auth()->user()->email }}" 
                                   readonly
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Contact Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="contact_number" 
                                   required
                                   placeholder="+63 912 345 6789"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8C5B56] focus:border-[#8C5B56]">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Shipping Address <span class="text-red-500">*</span>
                            </label>
                            <textarea name="shipping_address" 
                                      rows="4" 
                                      required
                                      placeholder="House/Unit No., Street, Barangay, City, Province, Postal Code"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8C5B56] focus:border-[#8C5B56]"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method" 
                                    required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8C5B56] focus:border-[#8C5B56]">
                                <option value="">Select Payment Method</option>
                                <option value="cod">Cash on Delivery</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Order Notes (Optional)
                            </label>
                            <textarea name="notes" 
                                      rows="3" 
                                      placeholder="Any special instructions for your order..."
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8C5B56] focus:border-[#8C5B56]"></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-[#8C5B56] text-white px-6 py-4 rounded-lg hover:bg-[#7A4E49] font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Place Order 🎉
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-8 sticky top-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                        @foreach($cartItems as $item)
                            <div class="flex gap-4 pb-4 border-b border-gray-200">
                                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500">Shop: {{ $item->product->shop_name }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                        <span class="font-bold text-[#8C5B56]">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="border-gray-200 mb-4">

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping Fee</span>
                            <span class="font-semibold text-green-600">FREE</span>
                        </div>
                    </div>

                    <hr class="border-gray-200 mb-4">

                    <div class="flex justify-between text-xl font-bold text-gray-900">
                        <span>Total</span>
                        <span class="text-[#8C5B56]">₱{{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('customer.cart.index') }}" 
                       class="block text-center text-[#8C5B56] hover:text-[#7A4E49] font-semibold mt-6 transition">
                        ← Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
@endsection