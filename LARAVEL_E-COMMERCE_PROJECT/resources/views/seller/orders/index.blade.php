@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Order List</title>

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
        .hover\:text-accent-dark:hover { color: #121338ff; }
    </style>
</head>

<body class="antialiased">
    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">Order List</h1>
                <p class="text-gray-500 text-sm mt-1">Overview of all customer orders</p>
            </div>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- ORDER TABLE CARD --}}
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 p-8">
            @if($orders->isEmpty())
                <div class="text-center py-16">
                    <h2 class="font-serif-elegant text-2xl text-gray-700 mb-2">No Orders Yet</h2>
                    <p class="text-gray-500 text-sm">You haven’t received any orders yet.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Buyer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Details</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50 transition-all duration-200">
                                    {{-- ORDER ID --}}
                                    <td class="px-6 py-4 text-gray-800 font-semibold">
                                        #{{ $order->order_number ?? 'ORD-' . strtoupper(substr($order->id, 0, 8)) }}
                                    </td>

                                    {{-- BUYER --}}
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $order->user->name ?? 'Unknown' }}
                                    </td>

                                    {{-- DATE --}}
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        {{ $order->created_at->format('F d, Y h:i A') }}
                                    </td>

                                    {{-- TOTAL --}}
                                    <td class="px-6 py-4 font-semibold text-accent">
                                        ₱{{ number_format($order->total_amount ?? 0, 2) }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4 text-sm">
                                        @if($order->status == 'pending')
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full border border-yellow-300">Pending</span>
                                        @elseif($order->status == 'completed')
                                            <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full border border-green-300">Completed</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full border border-red-300">Cancelled</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full border border-gray-300">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('seller.orders.show', $order->id) }}"
                                           class="text-accent text-sm font-semibold hover:underline hover:text-accent-dark transition duration-200">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
@endsection
