@php
    if (Auth::user()->role !== 'seller') {
        abort(403, 'Unauthorized');
    }
@endphp


@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 text-center">
    <h1 class="text-2xl font-bold text-gray-800">Seller Dashboard</h1>
    <p class="mt-2 text-gray-600">Welcome, {{ Auth::user()->name }}!</p>
    <p class="mt-4 text-gray-500">Here you can manage your products and categories.</p>
</div>
@endsection
