<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE</title>

    <script src="https://cdn.tailwindcss.com"></script>
<script>
    // Disable back navigation completely
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>


    <style>
        /* Define colors based on the Elle Fashion image (muted rose/brown for accent) */
        :root {
            --primary-text: #222222; 
            --secondary-text: #666666; 
            --accent-color: #8C5B56; /* Muted Rose/Brown from the image's buttons */
            --light-bg: #F5F5F5; /* Light grey background */
        }
        
        /* Importing an elegant serif font (Playfair Display) for titles and a clean sans-serif (Inter) for body text */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@100..900&display=swap');
        
        /* NOTE: Nag-adjust ako ng padding-top sa body para hindi matakpan ng fixed header ang content. */
        body {
            font-family: 'Inter', sans-serif; /* Default for body text */
            background-color: var(--light-bg); 
            color: var(--primary-text);
            /* Susing pagbabago: Magdagdag ng padding para sa fixed header (halos 70px) */
            padding-top: 72px; 
        }
        
        .font-serif-elegant {
            font-family: 'Playfair Display', serif; /* Custom class for high-fashion headings */
        }

        /* Custom Tailwind classes for the new style */
        .text-accent { color: var(--accent-color); }
        .bg-accent { background-color: var(--accent-color); }
        .hover\:bg-accent-dark:hover { background-color: #7A4E49; }
        
        /* Custom height utility for better responsiveness on the hero section to fit the screen */
        .h-hero {
            height: 50vh; 
            max-height: 600px; 
        }
        @media (min-width: 640px) {
            .h-hero {
                height: 70vh; 
            }
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="antialiased">

    {{-- START OF FIXED HEADER --}}
    <header class="fixed top-0 w-full z-10 bg-white shadow-md p-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between">
                
                {{-- LEFT SIDE: Category Filter Links --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index') }}" class="px-3 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full hover:bg-indigo-500 hover:text-white transition">All Products</a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.filter', $category) }}" class="px-3 py-1 text-sm bg-gray-200 text-gray-800 rounded-full hover:bg-indigo-500 hover:text-white transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                
                {{-- RIGHT SIDE: Auth Links (Login/Register/Dashboard/Logout) --}}
                <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
                    
                    @if (Route::has('login'))
                        @auth
                            @php
                                $role = Auth::user()->role ?? 'customer';
                            @endphp

                            @if ($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition">
                                    Admin Dashboard
                                </a>
                            @elseif ($role === 'seller')
                                <a href="{{ route('seller.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition">
                                    Seller Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition">
                                    My Dashboard
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-sm font-semibold text-white px-3 py-1 rounded transition bg-red-600 hover:bg-red-700">
                                    LOGOUT
                                </button>
                            </form>
                        @else
                            {{-- Login link --}}
                            <a href="{{ route('login') }}"
                                class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition">
                                LOGIN
                            </a>

                            @if (Route::has('register'))
                                {{-- Register link --}}
                                <a href="{{ route('register') }}"
                                    class="text-sm font-semibold text-white px-3 py-1 rounded transition bg-[#8C5B56] hover:bg-[#7A4E49]">
                                    REGISTER
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

            </div>
        </div>
    </header>
    {{-- END OF FIXED HEADER --}}

    {{-- CONTENT SECTION: Tinanggal ang p-4/sm:p-0 wrapper na kailangang tanggalin --}}
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Ang Main Content ay nagsisimula dito --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105">
                        
                        <div class="h-48 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h2 class="text-xl font-semibold text-gray-800 truncate">{{ $product->name }}</h2>
                            <p class="text-indigo-600 font-bold mt-1">P{{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-500 mt-1">Stock: {{ $product->stock }}</p>
                            <a href="{{ route('products.show', $product) }}" class="mt-3 block text-center bg-indigo-500 text-white py-2 rounded hover:bg-indigo-600 transition">
                                View Details
                            </a>
                        </div>
                        
                    </div>
                @empty
                    <p class="col-span-4 text-center text-gray-500 bg-white p-6 rounded-lg shadow">No products found. Please log in to the Seller Panel to add items.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>

        </div>
    </div>
 
    
    @include('partials.footer')
</body>
</html>