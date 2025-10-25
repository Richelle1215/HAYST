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
            --navy-blue: #000080; 
        }
        
        /* Importing an elegant serif font (Playfair Display) for titles and a clean sans-serif (Inter) for body text */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@100..900&display=swap');
        
        html {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg); 
            color: var(--primary-text);
            padding-top: 72px; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative; 
        }
        
        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }

        /* Custom Tailwind classes for the new style */
        .text-accent { color: var(--accent-color); }
        .bg-accent { background-color: var(--accent-color); }
        .hover\:bg-accent-dark:hover { background-color: #7A4E49; }
        
        /* Hero Section Styles */
        .hero-section {
            position: relative;
            height: 85vh;
            min-height: 500px;
            max-height: 800px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
            z-index: 1;
        }

        .hero-image {
            position: absolute;
            top: 0;
            right: 0;
            width: 60%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .hero-image {
                width: 100%;
                opacity: 0.4;
            }
            .hero-section {
                height: 70vh;
            }
        }

        .shine-effect {
            position: relative;
            overflow: hidden;
        }

        .shine-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .shine-effect:hover::before {
            left: 100%;
        }
        
        /* Custom class for Navy Blue text */
        .text-navy-blue { color: var(--navy-blue); }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="antialiased">

@extends('partials.header')

    {{-- HERO/COVER SECTION --}}
    <div class="hero-section">
        <img src="{{ asset('image/coverpage.webp') }}" alt="Luxury Jewelry" class="hero-image">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="max-w-2xl">
                    <h1 class="font-serif-elegant text-5xl sm:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                        Timeless <br>Elegance
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-200 mb-8 leading-relaxed">
                        Discover our exquisite collection of handcrafted jewelry, where luxury meets artistry. Each piece tells a unique story of elegance and sophistication.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#products" class="shine-effect inline-block bg-accent text-white px-8 py-4 rounded-lg font-semibold text-center hover:bg-accent-dark transition-all duration-300 transform hover:scale-105">
                            Explore Collection
                        </a>
                        <a href="#" class="inline-block border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-center hover:bg-white hover:text-gray-900 transition-all duration-300">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PRODUCTS SECTION --}}
    <div id="products" class="py-12 sm:py-20 flex-grow bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Title --}}
            <div class="text-center mb-12">
                <h2 class="font-serif-elegant text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    Our Collection
                </h2>
                <div class="w-24 h-1 bg-accent mx-auto mb-6"></div>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Handpicked pieces that embody sophistication and timeless beauty
                </p>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($products as $product)
                    <div class="bg-white shadow-xl rounded-xl overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-gray-100">
                        
                        <div class="h-64 overflow-hidden bg-gray-100 relative group">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-accent shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                New
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-serif-elegant text-xl font-semibold text-gray-900 mb-2 truncate">
                                {{ $product->name }}
                            </h3>
                            <p class="text-2xl font-bold text-accent mb-3">
                                ₱{{ number_format($product->price, 2) }}
                            </p>
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>{{ $product->stock }} in stock</span>
                            </div>
                            <a href="{{ route('products.show', $product) }}" 
                               class="block text-center bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent-dark transition-all duration-300 transform hover:translate-y-[-2px] shadow-md hover:shadow-lg">
                                View Details
                            </a>
                        </div>
                        
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="font-serif-elegant text-2xl font-semibold text-gray-700 mb-2">No Products Available</h3>
                        <p class="text-gray-500">Please log in to the Seller Panel to add items to your collection.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>

        </div>
    </div>
 
    {{-- FOOTER --}}
    @include('partials.footer') 
</body>
</html>