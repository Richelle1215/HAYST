@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-8">


    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-6">🗂 Categories</h1>

        <a href="{{ route('seller.categories.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-green-700">
            ➕ Add Category
        </a>

        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Category Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $category->id }}</td>
                    <td class="px-4 py-2">{{ $category->name }}</td>
                    <td class="px-4 py-2">{{ $category->description }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('seller.categories.edit', $category) }}" class="text-blue-600 hover:underline">Edit</a> |
                            <form action="{{ route('seller.categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                    Delete
                                </button>
                            </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</div>
{{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-80 text-center animate-fade-in">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Delete Category?</h2>
        <p class="text-gray-600 text-sm mb-5">Are you sure you want to delete this category? This action cannot be undone.</p>

        <div class="flex justify-center gap-3">
            <button id="cancelDelete" 
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('form[action*="categories"]');
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    const toast = document.getElementById('successToast');

    let selectedForm = null;

    // Show modal on delete click
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            selectedForm = form;
            modal.classList.remove('hidden');
        });
    });

    // Cancel button
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        selectedForm = null;
    });

    // Confirm delete
    confirmBtn.addEventListener('click', () => {
        if (selectedForm) {
            const action = selectedForm.getAttribute('action');
            const token = selectedForm.querySelector('input[name="_token"]').value;

                fetch(action, {
                    method: 'POST', // Use POST with _method override
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })

                .then(response => {
                if (response.ok) {
                    modal.classList.add('hidden');
                    selectedForm.closest('tr').remove(); // Remove row from table
                    toast.classList.remove('hidden');
                }
            });
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