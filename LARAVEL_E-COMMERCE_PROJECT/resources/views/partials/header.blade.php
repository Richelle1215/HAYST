{{-- resources/views/partials/header.blade.php (FINAL REVISION) --}}
<header class="bg-white shadow-md sticky top-0 z-50">
    {{-- FINAL FIX: Gumamit ng max-w-screen-2xl at mataas na px padding --}}
    <div class="max-w-screen-2xl mx-auto px-10 lg:px-16"> 
        
        {{-- MAIN NAVIGATION BAR --}}
        <div class="h-20 flex items-center justify-between">
            
            {{-- LOGO & NAVIGATION LINKS (Left Side) --}}
            <div class="flex items-center text-sm font-semibold tracking-wider uppercase flex-shrink-0">
                
                {{-- Logo Container (Dinagdagan pa ang mr) --}}
                <div class="mr-12"> 
                    <img
                        src="{{ asset('image/logo/blacktext-logo.png') }}" 
                        alt="Brand Logo"
                        class="h-10 w-auto" 
                    >
                </div>
                
                {{-- Navigation Links (Dinagdagan pa ng space-x) --}}
                <div class="flex items-center space-x-8"> 
                    <a href="#" class="text-gray-700 hover:text-[#8C5B56] transition">CATEGORIES</a>
                    <a href="#" class="text-gray-700 hover:text-[#8C5B56] transition">NEW ARRIVALS</a>
                    <a href="#" class="text-gray-700 hover:text-[#8C5B56] transition">SALE</a>
                </div>
            </div>

            {{-- SEARCH BAR (Center) --}}
            {{-- Ginawang w-2/5 at inayos ang mx --}}
            <div class="w-2/5 flex justify-center mx-12"> 
                <div class="relative w-full max-w-md">
                    <input 
                        type="search" 
                        placeholder="Search for clothes, bags, and more..." 
                        class="w-full pl-5 pr-12 py-2 border border-gray-300 rounded-lg text-base focus:ring-[#8C5B56] focus:border-[#8C5B56] transition"
                    >
                    
                    {{-- SEARCH ICON --}}
                    <button type="submit" 
                        class="absolute right-0 top-0 h-full w-10 bg-[#8C5B56] hover:bg-[#7A4E49] text-white rounded-r-lg flex items-center justify-center font-bold text-xl transition"
                    >
                        &#x1F50D; 
                    </button>
                </div>
            </div>

            {{-- USER ICONS / AUTH LINKS (Right Side) --}}
            <div class="flex items-center space-x-4 flex-shrink-0">
                
                {{-- Cart Icon --}}
                <a href="#" class="text-2xl text-gray-700 hover:text-[#8C5B56] transition mr-3">&#x1F6D2;</a> 
                
                {{-- Vertical Separator --}}
                <div class="h-6 w-px bg-gray-300"></div>

                {{-- Auth Links (Login/Register/Admin) --}}
                    @if (Route::has('login'))
                        @auth
                            @php
                                $role = Auth::user()->role ?? 'customer';
                            @endphp

                            @if ($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition ml-2">
                                    Admin Dashboard
                                </a>
                            @elseif ($role === 'seller')
                                <a href="{{ route('seller.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition ml-2">
                                    Seller Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition ml-2">
                                    My Dashboard
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-sm font-semibold text-white px-3 py-1 rounded transition bg-red-600 hover:bg-red-700 ml-2">
                                    LOGOUT
                                </button>
                            </form>
                        @else
                            {{-- Login link --}}
                            <a href="{{ route('login') }}"
                                class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition ml-2">
                                LOGIN
                            </a>

                            @if (Route::has('register'))
                                {{-- Register link --}}
                                <a href="{{ route('register') }}"
                                    class="text-sm font-semibold text-white px-3 py-1 rounded transition bg-[#8C5B56] hover:bg-[#7A4E49] ml-2">
                                    REGISTER
                                </a>
                            @endif
                        @endauth
                    @endif
            </div>
            
        </div>
        
    </div>
</header>