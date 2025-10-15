@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-6xl mx-auto">

    <h1 class="text-xl font-semibold mb-4 text-gray-800">Products</h1>

    <a href="{{ route('seller.products.create') }}" 
       class="bg-blue-700 hover:bg-blue-800 text-black text-sm px-4 py-2 rounded mb-4 inline-block">
       ➕ Add Product
    </a>

    <div class="overflow-x-auto">
        {{-- ✅ Full grid, all centered --}}
        <table class="w-full text-sm border border-gray-400 rounded-lg table-fixed border-collapse text-center">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 border border-gray-400 w-20">Image</th>
                    <th class="px-4 py-2 border border-gray-400">Name</th>
                    <th class="px-4 py-2 border border-gray-400">Price</th>
                    <th class="px-4 py-2 border border-gray-400 w-32">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    {{-- ✅ Product image (exact 40x40) --}}
                    <td class="px-4 py-2 border border-gray-400">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="object-cover rounded-md mx-auto" 
                                 width="40" height="40">
                        @else
                            <div class="h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center text-gray-400 text-xs mx-auto">
                                No Img
                            </div>
                        @endif
                    </td>

                    <td class="px-4 py-2 border border-gray-400 align-middle">{{ $product->name }}</td>
                    <td class="px-4 py-2 border border-gray-400 align-middle">₱{{ number_format($product->price, 2) }}</td>

                    <td class="px-4 py-2 border border-gray-400 align-middle">
                        <a href="{{ route('seller.products.edit', $product) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <span class="text-gray-400">|</span>
                        <form action="{{ route('seller.products.destroy', $product) }}" 
                              method="POST" class="inline">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-800 font-medium">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-gray-500 border border-gray-400">
                        No products found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-80 text-center animate-fade-in">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Delete Product?</h2>
        <p class="text-gray-600 text-sm mb-5">Are you sure you want to delete this product? This action cannot be undone.</p>

        <div class="flex justify-center gap-3">
            <button id="cancelDelete" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded-md">
                Cancel
            </button>
            <button id="confirmDelete" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('form[action*="seller/products/"]');
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');

    let selectedForm = null; // to track which form to submit

    // kapag nag-click ng delete button
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // huwag muna i-submit
            selectedForm = form;    // store yung form na pipindutin
            modal.classList.remove('hidden'); // ipakita ang modal
        });
    });

    // kapag pinindot ang cancel
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        selectedForm = null;
    });

    // kapag pinindot ang confirm delete
    confirmBtn.addEventListener('click', () => {
        if (selectedForm) {
            modal.classList.add('hidden');
            selectedForm.submit(); // tuloy delete
        }
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fade-in {
    animation: fadeIn 0.25s ease-out;
}
</style>
@endsection
