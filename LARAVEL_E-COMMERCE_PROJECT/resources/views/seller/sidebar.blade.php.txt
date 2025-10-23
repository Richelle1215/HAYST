<aside class="w-64 bg-gray-800 text-white fixed left-0 top-0 h-screen flex flex-col">
    <div class="p-6 text-center border-b border-gray-700">
        <h2 class="text-2xl font-bold">Seller Panel</h2>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('seller.dashboard') }}" 
           class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->is('seller/dashboard') ? 'bg-gray-700' : '' }}">
            🏠 Dashboard
        </a>
        <a href="{{ route('seller.products.index') }}" 
           class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->is('seller/products*') ? 'bg-gray-700' : '' }}">
            📦 Products
        </a>
        <a href="{{ route('seller.categories.index') }}" 
           class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->is('seller/categories*') ? 'bg-gray-700' : '' }}">
            🗂 Categories
        </a>
        <a href="#" 
           class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->is('seller/orders*') ? 'bg-gray-700' : '' }}">
            🧾 Orders
        </a>
        <a href="#" 
           class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->is('seller/profile*') ? 'bg-gray-700' : '' }}">
            👤 Profile
        </a>
    </nav>

    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                🚪 Logout
            </button>
        </form>
    </div>
</aside>
