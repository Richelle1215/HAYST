{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    
    {{-- 1. HEADER (Navigation Bar) --}}
    @include('partials.header')

    {{-- 2. MAIN CONTENT AREA (Dito ilalagay ang content ng bawat page) --}}
    <main>
        @yield('content') 
    </main>

    {{-- 3. FOOTER --}}
    @include('partials.footer')

</body>
</html>