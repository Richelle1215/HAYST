@extends('seller.layout')

@section('content')

<h1 class="text-2xl font-bold mb-6 text-gray-800">Order List</h1>

<div class="bg-white ">

    <!-- Subtitle -->
    <p class="text-sm text-gray-500 mb-6">Overview of all customer orders</p>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border rounded">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Order ID</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Buyer</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Date</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Total</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                    <th class="py-3 px-4 text-right text-sm font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- ORDER ID -->
                        <td class="py-3 px-4 text-gray-800 font-medium">
                            #{{ $order->order_number ?? 'ORD-' . strtoupper(substr($order->id, 0, 8)) }}
                        </td>

                        <!-- BUYER -->
                        <td class="py-3 px-4 text-gray-700">
                            {{ $order->user->name ?? 'Unknown' }}
                        </td>

                        <!-- DATE -->
                        <td class="py-3 px-4 text-gray-600">
                            {{ $order->created_at->format('F d, Y h:i A') }}
                        </td>

                        <!-- TOTAL -->
                        <td class="py-3 px-4 font-semibold text-green-600">
                            ₱{{ number_format($order->total_amount ?? 0, 2) }}
                        </td>

                        <!-- STATUS -->
                        <td class="py-3 px-4">
                            @if($order->status == 'pending')
                                <span class="bg-yellow-500 text-white text-xs font-medium px-3 py-1 rounded">
                                    Pending
                                </span>
                            @elseif($order->status == 'completed')
                                <span class="bg-green-600 text-white text-xs font-medium px-3 py-1 rounded">
                                    Completed
                                </span>
                            @elseif($order->status == 'cancelled')
                                <span class="bg-red-600 text-white text-xs font-medium px-3 py-1 rounded">
                                    Cancelled
                                </span>
                            @else
                                <span class="bg-gray-600 text-white text-xs font-medium px-3 py-1 rounded">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- ACTIONS -->
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('seller.orders.show', $order->id) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-xs font-medium transition">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
