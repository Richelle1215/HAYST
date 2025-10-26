<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex">
    <aside class="w-64 bg-white h-screen shadow-md p-4">
        <h2 class="font-bold text-xl mb-4">Customer Panel</h2>

        <ul class="space-y-3">
            <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('customer.profile') }}">Profile</a></li>
            <li><a href="{{ route('customer.orders') }}">Orders</a></li>
            <li><a href="{{ route('customer.cart') }}">Cart</a></li>
            <li><a href="{{ route('store.index') }}">Shop</a></li>
        </ul>
    </aside>

    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

</body>
</html>
