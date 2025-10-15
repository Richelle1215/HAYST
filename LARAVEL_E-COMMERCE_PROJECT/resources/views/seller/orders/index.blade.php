@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-8">
<h1 class="text-2xl font-bold mb-6 text-gray-800">Orders</h1>

@if ($orders->count())
    <table class="min-w-full bg-white rounded-lg shadow">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="py-2 px-4 text-left">Order ID</th>
                <th class="py-2 px-4 text-left">Customer</th>
                <th class="py-2 px-4 text-left">Total</th>
                <th class="py-2 px-4 text-left">Status</th>
                <th class="py-2 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr class="border-b">
                    <td class="py-2 px-4">{{ $order->id }}</td>
                    <td class="py-2 px-4">{{ $order->customer_name }}</td>
                    <td class="py-2 px-4">${{ number_format($order->total, 2) }}</td>
                    <td class="py-2 px-4">{{ ucfirst($order->status) }}</td>
                    <td class="py-2 px-4 text-center">
                        <a href="{{ route('seller.orders.show', $order->id) }}" class="btn-sidebar">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@else
    <p>No orders yet.</p>
@endif
@endsection
