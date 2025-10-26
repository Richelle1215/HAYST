@extends('seller.layout')

@section('content')

<h1 class="text-2xl font-bold mb-6 text-gray-800">Order Details</h1>

<div class="bg-white shadow rounded-lg p-6">

    <!-- Order Basic Info -->
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Order #{{ $order->order_number }}</h2>
        <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
    </div>

    <!-- Customer + Order Details -->
    <div class="border-t border-gray-200 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>Contact Number:</strong> {{ $order->contact_number }}</p>
        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Status:</strong>
            <span class="px-2 py-1 rounded text-white 
                @if($order->status == 'pending') bg-yellow-500 
                @elseif($order->status == 'completed') bg-green-600 
                @else bg-gray-600 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </p>

        <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>

        @if($order->notes)
            <p><strong>Notes:</strong> {{ $order->notes }}</p>
        @endif
    </div>

    <!-- Ordered Items Table -->
    <div class="mt-6">
        <h3 class="text-md font-semibold text-gray-700 mb-2">Items</h3>

        <table class="min-w-full border rounded">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4 text-left">Image</th>
                    <th class="py-2 px-4 text-left">Product</th>
                    <th class="py-2 px-4 text-left">Qty</th>
                    <th class="py-2 px-4 text-left">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr class="border-b">
                        <td class="py-2 px-4">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                No Image
                            @endif
                        </td>

                        <td class="py-2 px-4">{{ $item->product->name ?? 'N/A' }}</td>

                        <td class="py-2 px-4">{{ $item->quantity }}</td>

                        <td class="py-2 px-4 font-semibold">
                            ₱{{ number_format($item->quantity * $item->product->price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ✅ Back Button -->
    <div class="mt-6">
        <a href="{{ route('seller.orders.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
           ← Back to Orders
        </a>
    </div>

</div>

@endsection
