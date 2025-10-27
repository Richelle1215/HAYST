@extends('customer.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - LUMIÈRE</title>

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

        .tab-btn {
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .tab-btn.active {
            color: var(--accent-color);
            border-bottom: 3px solid var(--accent-color);
        }

        .tab-btn:hover {
            color: var(--accent-color);
        }

        .order-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #121338ff;
        }

        .status-badge {
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.35rem 0.75rem;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="min-h-screen antialiased">

    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">My Orders</h1>
                <p class="text-gray-500 text-sm mt-1">Track and manage your jewelry orders</p>
            </div>
        </div>

        {{-- STATUS TABS --}}
        <div class="bg-white shadow-lg rounded-2xl mb-8 overflow-hidden border border-gray-100">
            <div class="flex overflow-x-auto no-scrollbar border-b border-gray-200">
                @php
                    $tabs = [
                        'all' => ['label' => 'All Orders', 'icon' => ''],
                        'pending' => ['label' => 'To Pay', 'icon' => ''],
                        'processing' => ['label' => 'Processing', 'icon' => ''],
                        'shipped' => ['label' => 'Shipped', 'icon' => ''],
                        'completed' => ['label' => 'Completed', 'icon' => ''],
                        'cancelled' => ['label' => 'Cancelled', 'icon' => ''],
                    ];
                @endphp

                @foreach ($tabs as $key => $data)
                <button class="tab-btn px-8 py-4 text-gray-700 whitespace-nowrap flex items-center gap-2 {{ $loop->first ? 'active' : '' }}" 
                        data-status="{{ $key }}">
                    <span>{{ $data['icon'] }}</span>
                    <span>{{ $data['label'] }}</span>
                </button>
                @endforeach
            </div>
        </div>

        {{-- ORDERS --}}
        <div id="orders-container">
            @if($orders->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-16 text-center border border-gray-200">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h3 class="font-serif-elegant text-2xl text-gray-800 mb-3">No Orders Yet</h3>
                    <p class="text-gray-500 text-sm mb-6">You haven’t placed any orders yet.</p>
                    <a href="{{ route('store.index') }}"
                       class="btn-primary px-6 py-3 rounded-lg font-semibold text-sm">Shop Now</a>
                </div>
            @else
                @foreach($orders as $order)
                <div class="order-card mb-8 p-6" data-status="{{ $order->status }}">
                    {{-- Header --}}
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="font-semibold text-gray-800">Order #{{ $order->order_number }}</h2>
                            <p class="text-gray-500 text-sm">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                        </div>
                        @php
                            $statusColor = match($order->status) {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="status-badge {{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="">
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-gray-800 font-semibold">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</p>
                            </div>
                            <p class="text-accent font-semibold">
                                ₱{{ number_format($item->price * $item->quantity, 2) }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer (no View Details) --}}
                    <div class="mt-6 flex justify-end items-center border-t border-gray-100 pt-4">
                        <p class="text-gray-700 font-semibold">
                            Total: <span class="text-accent">₱{{ number_format($order->total_amount, 2) }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

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
                        const show = status === 'all' || status === card.dataset.status;
                        card.style.display = show ? 'block' : 'none';
                        if (show) visible++;
                    });

                    const msg = document.getElementById('no-orders-message');
                    if (!visible && cards.length) {
                        if (!msg) {
                            const div = document.createElement('div');
                            div.id = 'no-orders-message';
                            div.className = 'bg-white rounded-2xl shadow-lg p-16 text-center border border-gray-200';
                            div.innerHTML = `
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-gray-700 mb-2">No orders in this status</h3>
                                <p class="text-gray-500 text-sm">Try viewing another tab.</p>
                            `;
                            container.appendChild(div);
                        }
                    } else if (msg) msg.remove();
                });
            });
        });
    </script>

</body>
</html>
@endsection
