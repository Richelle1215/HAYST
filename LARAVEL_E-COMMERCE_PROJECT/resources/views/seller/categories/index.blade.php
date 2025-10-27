@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Categories</title>

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

    <div class="max-w-6xl mx-auto px-6 py-10">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-500 text-sm mt-1">Organize and manage your product categories</p>
            </div>
            <a href="{{ route('seller.categories.create') }}"
               class="bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                + Add Category
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- SEARCH BAR --}}
        <div class="mb-6">
            <div class="relative">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search categories by name or description..." 
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none shadow-sm">
                <svg class="w-5 h-5 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        {{-- CATEGORY TABLE --}}
        @if($categories->isEmpty())
            <div class="bg-white shadow-xl rounded-xl p-12 text-center border border-gray-100">
                <h2 class="font-serif-elegant text-2xl text-gray-700 mb-2">No Categories Yet</h2>
                <p class="text-gray-500">Start by adding your first category to organize your products.</p>
            </div>
        @else
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition-all duration-200 category-row"
                                data-name="{{ strtolower($category->name) }}"
                                data-description="{{ strtolower($category->description ?? '') }}">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $category->description ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('seller.categories.edit', $category) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <button onclick="openDeleteModal({{ $category->id }})"
                                            class="text-red-600 hover:text-red-900">Delete</button>
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
                <h3 class="font-serif-elegant text-xl text-gray-700 mb-2">No Categories Found</h3>
                <p class="text-gray-500">Try adjusting your search terms</p>
            </div>
        @endif
    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 w-[420px] text-center relative">
            <button onclick="closeDeleteModal()" 
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md">
                &times;
            </button>
            <h2 class="font-serif-elegant text-2xl text-gray-800 mb-3">Delete Category?</h2>
            <p class="text-gray-600 text-sm mb-5">
                Are you sure you want to delete this category? This action cannot be undone.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="closeDeleteModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded-md">
                    Cancel
                </button>
                <button id="confirmDelete"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-md">
                    Delete
                </button>
            </div>
        </div>
    </div>

    {{-- SUCCESS TOAST --}}
    <div id="successToast"
         class="hidden fixed top-5 right-5 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg text-sm z-50">
        ✅ Category deleted successfully.
    </div>

    <script>
        let deleteId = null;

        // SEARCH FUNCTIONALITY
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const categoryRows = document.querySelectorAll('.category-row');
            let visibleCount = 0;

            categoryRows.forEach(row => {
                const name = row.dataset.name;
                const description = row.dataset.description;
                
                if (name.includes(searchTerm) || description.includes(searchTerm)) {
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

        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteId = null;
        }

        document.getElementById('confirmDelete').addEventListener('click', () => {
            if (!deleteId) return;

            fetch(`/seller/categories/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    location.reload();
                } else {
                    alert('Error deleting category.');
                }
            })
            .catch(err => console.error(err));
        });
    </script>

</body>
</html>
@endsection