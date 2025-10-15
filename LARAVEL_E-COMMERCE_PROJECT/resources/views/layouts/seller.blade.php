<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} | Seller Panel</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- I-ensure na tama ang path ng iyong CSS/JS assets, karaniwan ay ito na ang standard --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    {{-- Ang 'body' tag na ito ay walang kasamang header o footer classes --}}
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            {{-- Dito ilalagay ang content mula sa dashboard.blade.php, index.blade.php, etc. --}}
            @yield('content')

        </div>

        @stack('modals')
        @livewireScripts
    </body>
</html>