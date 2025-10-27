@extends('seller.layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUMIÈRE | Profile Settings</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
        .hover\:text-accent:hover { color: var(--accent-color); }
    </style>
</head>

<body class="antialiased">
    <div class="max-w-4xl mx-auto px-6 py-10">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-10 border-b border-gray-200 pb-4">
            <div>
                <h1 class="font-serif-elegant text-4xl font-bold text-gray-900"> Profile Settings</h1>
                <p class="text-gray-500 text-sm mt-1">Manage your personal information and account security.</p>
            </div>
        </div>

        {{-- SUCCESS MESSAGES --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if (session('password_success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('password_success') }}
            </div>
        @endif


        {{-- UPDATE PERSONAL INFORMATION --}}
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 p-8 mb-10">
            <h2 class="font-serif-elegant text-2xl font-semibold text-gray-900 mb-3">Update Personal Information</h2>
            <p class="text-sm text-gray-500 mb-6">Edit your name and email address to keep your profile up-to-date.</p>

            <form method="POST" action="{{ route('seller.profile.update') }}">
                @csrf
                {{-- NAME --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $seller->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $seller->email) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-accent text-white text-sm font-semibold rounded-lg hover:bg-accent-dark transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>


        {{-- UPDATE PASSWORD --}}
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 p-8 mb-10">
            <h2 class="font-serif-elegant text-2xl font-semibold text-gray-900 mb-3">Update Password</h2>
            <p class="text-sm text-gray-500 mb-6">Keep your account secure by setting a strong password.</p>

            <form method="POST" action="{{ route('seller.profile.updatePassword') }}">
                @csrf
                {{-- CURRENT PASSWORD --}}
                <div class="mb-5">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none">
                    @error('current_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NEW PASSWORD --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-accent text-white text-sm font-semibold rounded-lg hover:bg-accent-dark transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>


        {{-- DELETE ACCOUNT --}}
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 p-8" x-data="{ confirmingUserDeletion: false }">
            <h2 class="font-serif-elegant text-2xl font-semibold text-red-700 mb-3">Delete Account</h2>
            <p class="text-sm text-gray-500 mb-6">Permanently delete your account. This action cannot be undone.</p>

            <button @click="confirmingUserDeletion = true"
                    class="px-6 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                Delete Account
            </button>

            {{-- CONFIRMATION MODAL --}}
            <div x-show="confirmingUserDeletion"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm p-4"
                 style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Account Deletion</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Once your account is deleted, all of its data will be permanently removed. Enter your password to confirm.
                    </p>

                    <form method="POST" action="{{ route('seller.profile.delete') }}">
                        @csrf
                        @method('delete')

                        <input type="password" id="password_to_delete" name="password_to_delete" placeholder="Password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        @error('password_to_delete')
                            <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                        @enderror

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="confirmingUserDeletion = false"
                                    class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-accent transition">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
@endsection
