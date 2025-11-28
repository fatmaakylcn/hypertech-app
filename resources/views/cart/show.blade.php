@extends('layouts.app')

@section('content')
<section class="page-heading">
    <h1>Sepetim</h1>
</section>

@if($items->isEmpty())
    <p>Sepetiniz boş. <a href="{{ route('products.index') }}">Ürünlere göz atın.</a></p>
@else
    <div class="table-wrapper">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th>Birim Fiyat</th>
                    <th>Adet</th>
                    <th>Toplam</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <small>{{ data_get($item->metadata, 'productInfo') }}</small>
                        </td>
                        <td>₺{{ number_format((float) $item->unit_price, 2, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="cart-actions">
                                @csrf
                                @method('PATCH')
                                <input
                                    type="number"
                                    name="quantity"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                    max="100"
                                    onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>₺{{ number_format($item->total, 2, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button button-danger" type="submit">Sil</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="cart-summary">
        Toplam: ₺{{ number_format($total, 2, ',', '.') }}
    </div>

    <p><a href="{{ route('products.index') }}">Alışverişe devam et</a></p>
@endif
@endsection
