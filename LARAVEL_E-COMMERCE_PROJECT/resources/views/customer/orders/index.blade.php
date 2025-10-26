@extends('customer.layout')

@section('content')

<div class="container-fluid px-4 py-6">

    <h1 class="text-4xl font-bold text-gray-900 mb-6 tracking-wide uppercase">My Orders</h1>

    {{-- Sticky Order Status Tabs --}}
    <div class="sticky top-0 z-20 bg-white shadow-sm mb-6 rounded-lg">
        <div class="flex border-b overflow-x-auto no-scrollbar">
            @php
                $tabs = [
                    'all' => 'All',
                    'to_pay' => 'To Pay',
                    'to_ship' => 'To Ship',
                    'to_receive' => 'To Receive',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'return_refund' => 'Return/Refund'
                ];
            @endphp

            @foreach ($tabs as $key => $label)
            <button class="tab-btn px-6 py-4 font-medium text-gray-700 hover:text-red-700 border-b-2
                border-transparent hover:border-red-700 whitespace-nowrap transition-all"
                data-status="{{ $key }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Orders Content --}}
    <div id="orders-container">
        @if($orders->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png"
                    class="mx-auto w-24 opacity-70 mb-4" alt="Empty Icon">

                <p class="text-gray-500 text-lg">No orders yet. Explore amazing jewelry!</p>

                <a href="{{ route('store.index') }}"
                    class="inline-block mt-6 px-6 py-3 bg-red-700 text-white font-semibold rounded-lg
                    hover:bg-red-800 transition">
                    Shop Now
                </a>
            </div>
        @else

        @foreach($orders as $order)
        <div class="order-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6"
             data-status="{{ $order->status }}">

            {{-- Order Header --}}
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div class="text-sm font-medium text-gray-600">
                    Order #{{ $order->id }}
                    <span class="text-gray-400 mx-2">|</span>
                    {{ $order->created_at->format('M d, Y h:i A') }}
                </div>

                @php
                    $statusColor = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-orange-100 text-orange-800'
                    ];
                @endphp

                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColor[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            {{-- Order Items --}}
            <div class="px-6 py-4 divide-y">
                @foreach($order->items as $item)
                <div class="flex gap-4 py-4">
                    <img src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}"
                         class="w-24 h-24 rounded-lg border border-gray-300 object-cover">

                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>

                        @if($item->product->variation)
                        <p class="text-sm text-gray-500">Variant: {{ $item->product->variation }}</p>
                        @endif

                        <p class="text-sm text-gray-500 mt-1">x{{ $item->quantity }}</p>
                    </div>

                    <div class="text-right">
                        <p class="text-lg font-bold text-red-700">
                            ₱{{ number_format($item->price, 2) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Progress Bar --}}
            <div class="relative w-full px-6 pb-4">
                @php
                    $progress = match($order->status) {
                        'pending' => 20,
                        'processing' => 50,
                        'shipped' => 80,
                        'completed' => 100,
                        default => 0,
                    };
                @endphp

                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-red-700 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Order Progress: {{ $progress }}%</p>
            </div>

            {{-- Order Footer --}}
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Total:
                    <span class="text-xl font-bold text-red-700">
                        ₱{{ number_format($order->total_amount, 2) }}
                    </span>
                </div>

                <div class="flex gap-3">
                    {{-- Action buttons based on status --}}
                    @if($order->status == 'pending')
                    <button class="px-5 py-2 bg-red-700 text-white font-medium rounded-lg hover:bg-red-800 transition">
                        Pay Now
                    </button>

                    @elseif($order->status == 'shipped')
                    <button class="px-5 py-2 bg-green-700 text-white font-medium rounded-lg hover:bg-green-800 transition">
                        Order Received
                    </button>

                    @elseif($order->status == 'completed')
                    <a href="{{ route('store.index') }}"
                       class="px-5 py-2 bg-red-700 text-white font-medium rounded-lg hover:bg-red-800 transition">
                        Buy Again
                    </a>
                    @endif

                    <a href="{{ route('customer.orders.show', $order->id) }}"
                       class="px-5 py-2 border border-gray-400 text-gray-800 font-medium rounded-lg hover:bg-gray-100 transition">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        @endif
    </div>

</div>

<style>
    .tab-btn.active {
        color: #b91c1c;
        border-bottom-color: #b91c1c;
        font-weight: 600;
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab-btn');
        const cards = document.querySelectorAll('.order-card');
        const container = document.getElementById('orders-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const status = tab.dataset.status;
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                let visible = 0;
                cards.forEach(card => {
                    const cardStatus = card.dataset.status;

                    const show =
                        status === 'all' ||
                        (status === 'to_pay' && cardStatus === 'pending') ||
                        (status === 'to_ship' && cardStatus === 'processing') ||
                        (status === 'to_receive' && cardStatus === 'shipped') ||
                        (status === 'completed' && cardStatus === 'completed') ||
                        (status === 'cancelled' && cardStatus === 'cancelled') ||
                        (status === 'return_refund' && cardStatus === 'refunded');

                    card.style.display = show ? 'block' : 'none';
                    if(show) visible++;
                });

                const msg = document.getElementById('no-orders-message');
                if (!visible && cards.length) {
                    if (!msg) {
                        const div = document.createElement('div');
                        div.id = 'no-orders-message';
                        div.className = 'bg-white rounded-lg shadow p-10 text-center text-gray-600';
                        div.innerHTML = `
                            <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png"
                                class="mx-auto w-20 opacity-70 mb-3">
                            No orders in this status.
                        `;
                        container.appendChild(div);
                    }
                } else if(msg) msg.remove();
            });
        });
    });
</script>

@endsection
