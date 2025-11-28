<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HyperTech Shop') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('products.index') }}" class="brand">HyperTech Shop</a>
        <nav class="nav">
            <a href="{{ route('products.index') }}">Ürünler</a>
            <a href="{{ route('cart.show') }}">Sepetim</a>
        </nav>
    </div>
</header>
<main class="container">
    @if(session('status'))
        <div class="alert success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
<footer class="site-footer">
    <div class="container">
        <small>Cache TTL: {{ $cacheTtl ?? config('hypertech.cache_ttl') }} sn •
            {{ now()->format('d.m.Y H:i') }}</small>
    </div>
</footer>
</body>
</html>

