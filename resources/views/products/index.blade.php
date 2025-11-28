@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Ürün Kataloğu</h1>
    </section>

    @if($error)
        <div class="alert error">{{ $error }}</div>
    @endif

    <div class="products-grid">
        @forelse($products as $product)
            <article class="product-card">
                <img src="{{ data_get($product, 'productData.productMainImage') }}"
                     alt="{{ $product['productName'] }}"
                     loading="lazy"
                     onerror="this.src='https://placehold.co/400x300/1a1d24/FFFFFF?text=No+Image';">
                <div class="product-body">
                    <h3 class="product-title">{{ $product['productName'] }}</h3>
                    <div class="product-price">
                        ₺{{ number_format((float) $product['salePrice'], 2, ',', '.') }}
                    </div>
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['productID'] }}">
                        <button type="submit" class="button">Sepete Ekle</button>
                    </form>
                </div>
            </article>
        @empty
            <p>Gösterilecek ürün bulunamadı.</p>
        @endforelse
    </div>

    @if($products->hasPages())
        <nav class="pagination" role="navigation">
            @if($products->onFirstPage())
                <span>Önceki</span>
            @else
                <a href="{{ $products->previousPageUrl() }}">Önceki</a>
            @endif

            <span class="active">Sayfa {{ $products->currentPage() }}</span>

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}">Sonraki</a>
            @else
                <span>Sonraki</span>
            @endif
        </nav>
    @endif
@endsection

