@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-6 mx-auto mt-8 w-full max-w-screen-2xl">

    <header class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">🗂 Categories</h1>
        <a href="{{ route('seller.categories.create') }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            ➕ Add Category
        </a>
    </header>

<div class="overflow-x-auto">
    <table class="min-w-full w-full border border-gray-300 rounded-lg shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-6 py-3 text-center text-sm font-semibold text-gray-700">Category Name</th>
                <th class="border border-gray-300 px-6 py-3 text-center text-sm font-semibold text-gray-700">Description</th>
                <th class="border border-gray-300 px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="border border-gray-300 px-6 py-3 text-center">{{ $category->name }}</td>
                <td class="border border-gray-300 px-6 py-3 text-center">{{ $category->description }}</td>
                <td class="border border-gray-300 px-6 py-3 text-center">
                    <a href="{{ route('seller.categories.edit', $category) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('seller.categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>




</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center animate-fade-in">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Delete Category?</h2>
        <p class="text-gray-600 text-sm mb-5">
            Are you sure you want to delete this category? This action cannot be undone.
        </p>
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

{{-- SUCCESS TOAST --}}
<div id="successToast"
     class="hidden fixed top-5 right-5 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg text-sm z-50">
    ✅ Category deleted successfully.
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('form[action*="categories"]');
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    const toast = document.getElementById('successToast');

    let selectedForm = null;

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            selectedForm = form;
            modal.classList.remove('hidden');
        });
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        selectedForm = null;
    });

    confirmBtn.addEventListener('click', () => {
        if (!selectedForm) return;
        const action = selectedForm.getAttribute('action');
        const token = selectedForm.querySelector('input[name="_token"]').value;

        fetch(action, {
            method: 'POST', // _method DELETE
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ _method: 'DELETE' })
        }).then(response => {
            if (response.ok) {
                modal.classList.add('hidden');
                selectedForm.closest('tr').remove();
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 3000);
            }
        });
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
