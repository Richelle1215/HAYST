@extends('seller.layout') 

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">👤 Profile Settings</h1>

        {{-- Success Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('password_success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('password_success') }}</span>
            </div>
        @endif

        {{-- 1. Update Profile Information Form (Edit Info) --}}
        <div class="bg-white border border-gray-200 p-6 rounded-lg mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Personal Information</h2>
            <p class="text-sm text-gray-600 mb-6">Update your account's profile information and email address.</p>

            <form method="POST" action="{{ route('seller.profile.update') }}">
                @csrf
                {{-- Name Field --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $seller->name) }}" required autofocus autocomplete="name"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Email Field --}}
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $seller->email) }}" required autocomplete="email"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>


        {{-- 2. Update Password Form (Change Password) --}}
        <div class="bg-white border border-gray-200 p-6 rounded-lg mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Password</h2>
            <p class="text-sm text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

            <form method="POST" action="{{ route('seller.profile.updatePassword') }}">
                @csrf
                {{-- Current Password --}}
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('current_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- New Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        
        {{-- 3. Delete Account Section --}}
        <div class="bg-red-50 border border-red-300 p-6 rounded-lg" x-data="{ confirmingUserDeletion: false }">
            <h2 class="text-xl font-semibold text-red-800 mb-4">Delete Account</h2>
            <p class="text-sm text-red-600 mb-6">Permanently delete your account. This action cannot be undone.</p>
            
            <button @click="confirmingUserDeletion = true" type="button" 
                    class="px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 transition">
                Delete Account
            </button>
            
            {{-- Confirmation Modal (Requires Alpine.js) --}}
            <div x-show="confirmingUserDeletion" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Modal Background --}}
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="confirmingUserDeletion = false"></div>

                    {{-- Modal Panel --}}
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form method="POST" action="{{ route('seller.profile.delete') }}">
                            @csrf
                            @method('delete')

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Are you sure you want to delete your account?
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Once your account is deleted, all of its resources and data will be permanently erased. Please enter your password to confirm you would like to permanently delete your account.
                                    </p>
                                    
                                    <div class="mt-4">
                                        <label for="password_to_delete" class="sr-only">Password</label>
                                        <input type="password" id="password_to_delete" name="password_to_delete" placeholder="Password" required
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                        @error('password_to_delete')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Delete Account
                                </button>
                                <button type="button" @click="confirmingUserDeletion = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection