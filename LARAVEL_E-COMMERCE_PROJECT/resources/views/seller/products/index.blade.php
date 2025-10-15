<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create New Category') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    <div class="col-span-6 sm:col-span-4 mb-4">
                        <x-label for="name" value="{{ __('Category Name') }}" />
                        <x-input id="name" type="text" name="name" class="mt-1 block w-full" value="{{ old('name') }}" required />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-4">
                        <x-label for="description" value="{{ __('Description (Optional)') }}" />
                        <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <x-button class="mt-4">
                        {{ __('Save Category') }}
                    </x-button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>