@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Order Details</title>

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
    <div class="max-w-6xl mx-auto px-6 py-10">
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">Order Details</h1>
                <p class="text-gray-500 text-sm mt-1">Full summary of customer’s order</p>
            </div>
            <a href="{{ route('seller.orders.index') }}" 
               class="bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                ← Back to Orders
            </a>
        </div>

        {{-- ORDER INFO CARD --}}
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 p-8 mb-8">
            <h2 class="font-serif-elegant text-2xl text-gray-800 mb-4">Order #{{ $order->order_number }}</h2>
            <p class="text-sm text-gray-500 mb-6">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Contact Number:</strong> {{ $order->contact_number }}</p>
                <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-700 border border-yellow-300
                        @elseif($order->status == 'completed') bg-green-100 text-green-700 border border-green-300
                        @else bg-gray-100 text-gray-700 border border-gray-300 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>

                @if($order->notes)
                    <p class="md:col-span-2"><strong>Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>

        {{-- ORDER ITEMS TABLE --}}
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 p-8">
            <h3 class="font-serif-elegant text-xl text-gray-800 mb-4">Items</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->orderItems as $item)
                            <tr class="hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-4">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center text-gray-500 text-xs">
                                            No Image
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-800 font-medium">
                                    {{ $item->product->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 font-semibold text-accent">
                                    ₱{{ number_format($item->quantity * ($item->product->price ?? 0), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
