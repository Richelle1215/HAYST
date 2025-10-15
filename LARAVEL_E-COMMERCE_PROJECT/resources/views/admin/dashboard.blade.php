@php
    if (Auth::user()->role !== 'seller') {
        abort(403, 'Unauthorized');
    }
@endphp

@extends('layouts.app')

@section('content')
    <div class="container py-5 text-center">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>You are logged in as <strong>{{ Auth::user()->role }}</strong>.</p>
    </div>
@endsection
