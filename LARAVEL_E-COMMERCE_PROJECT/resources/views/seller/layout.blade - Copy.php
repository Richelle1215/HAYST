<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Seller Panel</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* ✅ Sidebar Settings */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1f2937; /* Tailwind gray-800 */
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 15px rgba(31, 41, 55, 0.5); /* subtle right shadow */
        }

        /* ✅ Main content area */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            background-color: #f9fafb;
            min-height: 100vh;
            width: calc(100% - 260px);
            box-shadow: 0 0 20px rgba(30, 58, 138, 0.4); /* dark blue shadow around content */
            border-radius: 10px;
        }

        /* ✅ Sidebar links */
        .sidebar a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #d1d5db;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            font-weight: 500;
            border-radius: 6px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #374151; /* gray-700 */
            color: #fff;
        }

        /* ✅ Sidebar title */
        .sidebar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* ✅ Logout button */
        .sidebar button {
            background-color: #dc2626; /* red-600 */
            padding: 0.75rem;
            border-radius: 0.375rem;
            width: 100%;
            transition: background 0.2s;
        }

        .sidebar button:hover {
            background-color: #b91c1c; /* red-700 */
        }

        /* ✅ Optional smooth scroll */
        body {
            scroll-behavior: smooth;
        }

        /* ✅ Responsive adjustments */
        @media (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
                width: calc(100% - 220px);
            }
        }

        /* ✅ Table header theme (blue-gray mix) */
        table thead {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 80%, #374151 100%);
            color: #f3f4f6; /* Tailwind gray-100 */
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="flex">
        {{-- ✅ Sidebar --}}
        <aside class="sidebar">
            <div>
                <div class="p-6 text-center border-b border-gray-700">
                    <h2>Seller Panel</h2>
                </div>

                <nav class="p-4 space-y-2">
                    <a href="{{ route('seller.dashboard') }}" 
                       class="{{ request()->is('seller/dashboard') ? 'active' : '' }}">
                       🏠 Dashboard
                    </a>
                    <a href="{{ route('seller.products.index') }}" 
                       class="{{ request()->is('seller/products*') ? 'active' : '' }}">
                       📦 Products
                    </a>
                    <a href="{{ route('seller.categories.index') }}" 
                       class="{{ request()->is('seller/categories*') ? 'active' : '' }}">
                       🗂 Categories
                    </a>
                    <a href="{{ route('seller.orders.index') }}" 
                       class="{{ request()->is('seller/orders*') ? 'active' : '' }}">
                       🧾 Orders
                    </a>
                    <a href="{{ route('seller.profile.index') }}" 
                       class="{{ request()->is('seller/profile*') ? 'active' : '' }}">
                       👤 Profile
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white font-semibold">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ✅ Main content --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>
