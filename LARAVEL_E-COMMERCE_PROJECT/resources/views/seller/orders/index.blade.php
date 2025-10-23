@extends('seller.layout') {{-- Dapat tama ang path ng seller layout mo --}}

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-xl">
        {{-- Header at Buttons --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Orders</h1>
            {{-- ... Export at More Actions Buttons ... --}}
        </div>


     
        <p class="text-sm text-gray-500 mb-6">Overview of total orders, returns, and revenue.</p>

        {{-- Table Structure --}}
        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                {{-- Table Headers --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                {{-- Table Body (Looping through $orders) --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- DITO KA NAG-FOOREACH NG $ORDERS --}}
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            {{-- ... Iba pang table cells ... --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection