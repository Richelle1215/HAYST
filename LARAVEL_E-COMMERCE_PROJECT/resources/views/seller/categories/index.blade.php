@extends('seller.layout')

@section('content')
<div class="bg-white shadow rounded-lg p-8">
    @include('seller.sidebar')

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
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</div>
@endsection