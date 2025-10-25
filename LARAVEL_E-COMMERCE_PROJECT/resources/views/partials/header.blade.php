{{-- START OF FIXED HEADER --}}
<header class="fixed top-0 w-full z-10 bg-white shadow-md p-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between">
            
            {{-- LEFT SIDE: Category Dropdown (Using Plain JavaScript) --}}
            <div class="relative z-20">
                
                {{-- Dropdown Toggle Button --}}
                <button onclick="toggleCategories()" 
                        type="button"
                        id="categoriesButton"
                        class="px-4 py-2 text-sm bg-indigo-100 text-indigo-700 rounded-lg shadow hover:bg-indigo-500 hover:text-white transition flex items-center">
                    Categories
                    {{-- Dropdown Icon --}}
                    <svg id="dropdownIcon" class="w-4 h-4 ml-1 transition-transform" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                {{-- Dropdown List --}}
                <div id="categoriesDropdown"
                     style="display: none;"
                     class="absolute left-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none origin-top-left">
                    
                    <div class="py-1">
                        {{-- Link para sa Lahat ng Produkto --}}
                        <a href="{{ route('products.index') }}" 
                           class="block px-4 py-2 text-sm text-indigo-700 hover:bg-indigo-50 hover:text-indigo-900 transition">
                            All Products
                        </a>
                        
                        {{-- Category List --}}
                        @foreach($categories as $category)
                            <a href="{{ route('products.filter', $category) }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-900 transition">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div> {{-- End of Dropdown --}}
            
            {{-- CENTER: Shop Name (LUMIÈRE) --}}
            <a href="{{ route('products.index') }}" class="text-3xl font-serif-elegant font-bold text-navy-blue tracking-wider hover:text-gray-900 transition absolute left-1/2 transform -translate-x-1/2">
                LUMIÈRE
            </a>

            {{-- RIGHT SIDE: Auth Links --}}
            <div class="flex flex-wrap gap-2 items-center">
                @if (Route::has('login'))
                    @auth
                        @php
                            $role = Auth::user()->role ?? 'customer';
                        @endphp

                        @if ($role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Admin Dashboard</a>
                        @elseif ($role === 'seller')
                            <a href="{{ route('seller.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Seller Dashboard</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Customer Dashboard</a>
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
                                class="text-sm font-semibold text-gray-700 hover:text-[#8C5B56] transition">
                                REGISTER
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

        </div>
    </div>
</header>

{{-- JavaScript para sa Dropdown --}}
<script>
    function toggleCategories() {
        const dropdown = document.getElementById('categoriesDropdown');
        const icon = document.getElementById('dropdownIcon');
        
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            dropdown.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('categoriesDropdown');
        const button = document.getElementById('categoriesButton');
        
        if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
            document.getElementById('dropdownIcon').style.transform = 'rotate(0deg)';
        }
    });
</script>