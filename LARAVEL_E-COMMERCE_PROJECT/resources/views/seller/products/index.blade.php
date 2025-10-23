
@extends('seller.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Products</h1>
        <a href="{{ route('seller.products.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md">
            + Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <p class="text-gray-600">No products yet. Start by adding your first product!</p>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="openProductModal({{ $product->id }})">
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₱{{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                <a href="{{ route('seller.products.edit', $product->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <button onclick="deleteProduct({{ $product->id }})" 
                                            class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>

{{-- Product Detail Modal --}}
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative bg-white rounded-xl shadow-2xl w-[600px] max-h-[85vh] overflow-hidden">
        {{-- Close Button --}}
        <button onclick="closeProductModal()" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md">
            &times;
        </button>

        {{-- Modal Content --}}
        <div class="p-6 overflow-y-auto max-h-[85vh]">
            {{-- Product Image --}}
            <div class="mb-4">
                <img id="modalImage" src="" alt="" class="w-full h-64 object-contain bg-gray-50 rounded-lg">
            </div>

            {{-- Product Details --}}
            <div>
                <h2 id="modalName" class="text-2xl font-bold text-gray-800 mb-3"></h2>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-600 w-24">Category:</span>
                        <span id="modalCategory" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold"></span>
                    </div>

                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-600 w-24">Price:</span>
                        <span id="modalPrice" class="text-xl font-bold text-green-600"></span>
                    </div>

                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-600 w-24">Stock:</span>
                        <span id="modalStock" class="text-base font-semibold text-gray-800"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Description:</h3>
                    <p id="modalDescription" class="text-gray-700 text-sm leading-relaxed max-h-24 overflow-y-auto"></p>
                </div>

                {{-- The following <div> containing the Edit and Delete buttons is REMOVED --}}
                {{-- <div class="flex gap-3 mt-4">
                    <a id="modalEditBtn" href="#" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2.5 rounded-lg shadow-md font-semibold text-sm">
                        Edit Product
                    </a>
                    <button id="modalDeleteBtn" onclick="deleteProductFromModal()" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg shadow-md font-semibold text-sm">
                        Delete Product
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<script>
// Store products data for modal
const productsData = @json($products->items());

// Open modal with product details
function openProductModal(productId) {
    const product = productsData.find(p => p.id === productId);
    if (!product) return;

    // Set image
    const modalImage = document.getElementById('modalImage');
    if (product.image) {
        modalImage.src = `/storage/${product.image}`;
        modalImage.alt = product.name;
    } else {
        modalImage.src = '';
        modalImage.alt = 'No image';
        modalImage.classList.add('bg-gray-200');
    }

    // Set details
    document.getElementById('modalName').textContent = product.name;
    document.getElementById('modalCategory').textContent = product.category?.name || 'N/A';
    document.getElementById('modalPrice').textContent = '₱' + parseFloat(product.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('modalStock').textContent = product.stock + ' units';
    document.getElementById('modalDescription').textContent = product.description;

    // The following two lines are no longer strictly necessary but can be left as they won't cause an error
    // document.getElementById('modalEditBtn').href = `/seller/products/${productId}/edit`;
    // document.getElementById('modalDeleteBtn').setAttribute('data-product-id', productId);

    // Show modal
    document.getElementById('productModal').classList.remove('hidden');
}

// Close modal
function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
}

// Delete product from modal - THIS FUNCTION IS NOW UNUSED
// function deleteProductFromModal() {
//     const productId = document.getElementById('modalDeleteBtn').getAttribute('data-product-id');
//     deleteProduct(productId);
// }

// Delete product function - THIS FUNCTION IS STILL USED BY THE MAIN TABLE'S DELETE BUTTON
function deleteProduct(productId) {
    if (!confirm('Are you sure you want to delete this product?')) return;

    fetch(`/seller/products/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeProductModal();
            location.reload();
        } else {
            alert('Error deleting product: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting product');
    });
}

// Close modal when clicking outside
document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProductModal();
    }
});
</script>
@endsection