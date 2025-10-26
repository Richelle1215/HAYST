@extends('customer.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - LUMIÈRE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }

        .tab-btn {
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-btn.active {
            color: #8C5B56;
            font-weight: 600;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #8C5B56, #A67C75);
            border-radius: 3px 3px 0 0;
        }

        .tab-btn:hover {
            color: #8C5B56;
            transform: translateY(-2px);
        }

        .order-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: #8C5B56;
        }

        .progress-bar-animated {
            animation: progressAnimation 0.8s ease-out;
        }

        @keyframes progressAnimation {
            from { width: 0; }
        }

        .status-badge {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #8C5B56 0%, #A67C75 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(140, 91, 86, 0.3);
        }

        .product-image {
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="container mx-auto px-4 py-12 mt-16 max-w-7xl">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="font-serif-elegant text-5xl font-bold text-gray-900 mb-2">My Orders</h1>
            <p class="text-gray-600 text-lg">Track and manage your jewelry orders</p>
        </div>

        <!-- Sticky Order Status Tabs -->
        <div class="sticky top-16 z-20 bg-white shadow-xl mb-8 rounded-2xl overflow-hidden">
            <div class="flex border-b-2 border-gray-100 overflow-x-auto no-scrollbar">
                @php
                    $tabs = [
                        'all' => ['label' => 'All Orders', 'icon' => '📦'],
                        'pending' => ['label' => 'To Pay', 'icon' => '💳'],
                        'processing' => ['label' => 'Processing', 'icon' => '⏳'],
                        'shipped' => ['label' => 'Shipped', 'icon' => '🚚'],
                        'completed' => ['label' => 'Completed', 'icon' => '✅'],
                        'cancelled' => ['label' => 'Cancelled', 'icon' => '❌'],
                    ];
                @endphp

                @foreach ($tabs as $key => $data)
                <button class="tab-btn px-8 py-5 font-medium text-gray-700 whitespace-nowrap transition-all flex items-center gap-2 {{ $loop->first ? 'active' : '' }}"
                        data-status="{{ $key }}">
                    <span class="text-2xl">{{ $data['icon'] }}</span>
                    <span>{{ $data['label'] }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Orders Content -->
        <div id="orders-container">
            @if($orders->isEmpty())
                <div class="bg-white rounded-3xl shadow-2xl p-16 text-center">
                    <div class="mb-6">
                        <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-3">No Orders Yet</h3>
                    <p class="text-gray-500 text-lg mb-8">Discover our exquisite jewelry collection!</p>
                    <a href="{{ route('store.index') }}"
                       class="inline-block px-10 py-4 btn-primary text-white font-bold rounded-xl text-lg shadow-lg">
                        Start Shopping ✨
                    </a>
                </div>
            @else

            @foreach($orders as $order)
            <div class="order-card bg-white rounded-2xl shadow-lg overflow-hidden mb-6"
                 data-status="{{ $order->status }}">

                <!-- Order Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-5 flex justify-between items-center border-b-2 border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="text-sm font-semibold text-gray-800">
                            <span class="text-[#8C5B56]">Order #{{ $order->order_number }}</span>
                        </div>
                        <span class="text-gray-400">•</span>
                        <div class="text-sm text-gray-600">
                            {{ $order->created_at->format('M d, Y • h:i A') }}
                        </div>
                    </div>

                    @php
                        $statusConfig = [
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '⏱️'],
                            'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '🔄'],
                            'shipped' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => '📦'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '✅'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '❌'],
                        ];
                        $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => '📋'];
                    @endphp

                    <span class="status-badge px-4 py-2 text-sm font-bold rounded-full {{ $config['bg'] }} {{ $config['text'] }} flex items-center gap-2">
                        <span>{{ $config['icon'] }}</span>
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Order Items -->
                <div class="px-8 py-6 divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <div class="flex gap-6 py-5 items-center">
                        <div class="w-28 h-28 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 shadow-md">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover product-image">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">
                                <span class="font-semibold">Shop:</span> {{ $item->product->shop_name ?? 'N/A' }}
                            </p>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                    Qty: <span class="font-bold">{{ $item->quantity }}</span>
                                </span>
                                <span class="text-sm text-gray-400">×</span>
                                <span class="text-sm text-gray-600">₱{{ number_format($item->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#8C5B56]">
                                ₱{{ number_format($item->price * $item->quantity, 2) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Progress Bar -->
                <div class="px-8 pb-6">
                    @php
                        $progress = match($order->status) {
                            'pending' => 25,
                            'processing' => 50,
                            'shipped' => 75,
                            'completed' => 100,
                            default => 0,
                        };
                    @endphp

                    <div class="relative">
                        <div class="flex justify-between text-xs font-semibold text-gray-600 mb-2">
                            <span>Order Progress</span>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                            <div class="progress-bar-animated bg-gradient-to-r from-[#8C5B56] to-[#A67C75] h-3 rounded-full transition-all duration-1000" 
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Order Footer -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 flex justify-between items-center border-t-2 border-gray-200">
                    <div class="flex items-baseline gap-2">
                        <span class="text-sm font-medium text-gray-600">Order Total:</span>
                        <span class="text-3xl font-bold text-[#8C5B56]">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </span>
                    </div>

                    <div class="flex gap-3">
                        @if($order->status == 'pending')
                        <button class="px-6 py-3 btn-primary text-white font-bold rounded-xl shadow-lg">
                            💳 Pay Now
                        </button>

                        @elseif($order->status == 'shipped')
                        <button class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                            ✅ Order Received
                        </button>

                        @elseif($order->status == 'completed')
                        <a href="{{ route('store.index') }}"
                           class="px-6 py-3 btn-primary text-white font-bold rounded-xl shadow-lg">
                            🛍️ Buy Again
                        </a>
                        @endif

                        <a href="{{ route('customer.orders.show', $order->id) }}"
                           class="px-6 py-3 border-2 border-[#8C5B56] text-[#8C5B56] font-bold rounded-xl hover:bg-[#8C5B56] hover:text-white transition-all">
                            View Details →
                        </a>
                    </div>
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
                    
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    let visible = 0;
                    cards.forEach(card => {
                        const cardStatus = card.dataset.status;
                        const show = status === 'all' || status === cardStatus;

                        card.style.display = show ? 'block' : 'none';
                        if(show) visible++;
                    });

                    // Show "no orders" message
                    const msg = document.getElementById('no-orders-message');
                    if (!visible && cards.length) {
                        if (!msg) {
                            const div = document.createElement('div');
                            div.id = 'no-orders-message';
                            div.className = 'bg-white rounded-3xl shadow-2xl p-16 text-center';
                            div.innerHTML = `
                                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="text-2xl font-bold text-gray-700 mb-2">No orders in this status</h3>
                                <p class="text-gray-500">Check other tabs to view your orders</p>
                            `;
                            container.appendChild(div);
                        }
                    } else if(msg) {
                        msg.remove();
                    }
                });
            });
        });
    </script>

</body>
</html>
@endsection