@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | My Products</title>

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
    </style>
</head>

<body class="antialiased">

    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">My Products</h1>
                <p class="text-gray-500 text-sm mt-1">Manage and showcase your store collection</p>
            </div>
            <a href="{{ route('seller.products.create') }}" 
               class="bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                + Add New Product
            </a>
        </div>

        {{-- SEARCH BAR --}}
        <div class="mb-6">
            <div class="relative">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search products by name, category, or price..." 
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none shadow-sm">
                <svg class="w-5 h-5 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- PRODUCT TABLE --}}
        @if($products->isEmpty())
            <div class="bg-white shadow-xl rounded-xl p-12 text-center border border-gray-100">
                <h2 class="font-serif-elegant text-2xl text-gray-700 mb-2">No Products Yet</h2>
                <p class="text-gray-500">Start by adding your first product to your collection.</p>
            </div>
        @else
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 cursor-pointer transition-all duration-200 product-row" 
                                onclick="openProductModal({{ $product->id }})"
                                data-name="{{ strtolower($product->name) }}"
                                data-category="{{ strtolower($product->category->name ?? '') }}"
                                data-price="{{ $product->price }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                            alt="{{ $product->name }}" 
                                            class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs">
                                            No image
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-accent">
                                    ₱{{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                    <a href="{{ route('seller.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <button onclick="deleteProduct({{ $product->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- NO RESULTS MESSAGE --}}
            <div id="noResultsMessage" class="hidden bg-white shadow-xl rounded-xl p-12 text-center border border-gray-100 mt-6">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="font-serif-elegant text-xl text-gray-700 mb-2">No Products Found</h3>
                <p class="text-gray-500">Try adjusting your search terms</p>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL --}}
    <div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-xl shadow-2xl w-[600px] max-h-[85vh] overflow-hidden">
            <button onclick="closeProductModal()" 
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md">
                &times;
            </button>
            <div class="p-6 overflow-y-auto max-h-[85vh]">
                <div class="mb-4">
                    <img id="modalImage" src="" alt="" class="w-full h-64 object-contain bg-gray-50 rounded-lg">
                </div>
                <div>
                    <h2 id="modalName" class="text-2xl font-serif-elegant font-bold text-gray-800 mb-3"></h2>
                    <div class="space-y-2 mb-4">
                        <p><strong>Category:</strong> <span id="modalCategory" class="text-blue-700 font-semibold"></span></p>
                        <p><strong>Price:</strong> <span id="modalPrice" class="text-green-600 font-bold"></span></p>
                        <p><strong>Stock:</strong> <span id="modalStock"></span></p>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Description:</h3>
                    <p id="modalDescription" class="text-gray-700 text-sm leading-relaxed"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const productsData = @json($products->items());

        // SEARCH FUNCTIONALITY
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const productRows = document.querySelectorAll('.product-row');
            let visibleCount = 0;

            productRows.forEach(row => {
                const name = row.dataset.name;
                const category = row.dataset.category;
                const price = row.dataset.price;
                
                if (name.includes(searchTerm) || category.includes(searchTerm) || price.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResultsMsg = document.getElementById('noResultsMessage');
            if (visibleCount === 0 && searchTerm !== '') {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
            }
        });

        function openProductModal(productId) {
            const p = productsData.find(x => x.id === productId);
            if (!p) return;

            document.getElementById('modalImage').src = p.image ? `/storage/${p.image}` : '';
            document.getElementById('modalName').textContent = p.name;
            document.getElementById('modalCategory').textContent = p.category?.name || 'N/A';
            document.getElementById('modalPrice').textContent = '₱' + parseFloat(p.price).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('modalStock').textContent = `${p.stock} units`;
            document.getElementById('modalDescription').textContent = p.description || 'No description available.';

            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        function deleteProduct(productId) {
            if (!confirm('Are you sure you want to delete this product?')) return;

            fetch(`/seller/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeProductModal();
                    location.reload();
                } else {
                    alert('Error deleting product: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error deleting product');
            });
        }
    </script>

</body>
</html>
@endsection