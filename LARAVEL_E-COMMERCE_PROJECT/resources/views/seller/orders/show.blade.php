@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-8">
<h1 class="text-2xl font-bold mb-6 text-gray-800">Order Details</h1>

<div class="bg-white shadow rounded-lg p-6">
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Order #{{ $order->id }}</h2>
        <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
    </div>

    <div class="border-t border-gray-200 pt-4">
        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
        <p><strong>Status:</strong> 
            <span class="px-2 py-1 rounded text-white 
                @if($order->status == 'pending') bg-yellow-500 
                @elseif($order->status == 'completed') bg-green-600 
                @else bg-gray-600 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </p>
        <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
    </div>

    <div class="mt-6">
        <h3 class="text-md font-semibold text-gray-700 mb-2">Items</h3>
        <table class="min-w-full border rounded">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4 text-left">Product</th>
                    <th class="py-2 px-4 text-left">Qty</th>
                    <th class="py-2 px-4 text-left">Price</th>
                    <th class="py-2 px-4 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $item->product_name }}</td>
                        <td class="py-2 px-4">{{ $item->quantity }}</td>
                        <td class="py-2 px-4">₱{{ number_format($item->price, 2) }}</td>
                        <td class="py-2 px-4">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('seller.orders.index') }}" class="btn-sidebar">← Back to Orders</a>
    </div>
</div>
@endsection
