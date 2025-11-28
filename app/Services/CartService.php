<?php

namespace App\Services;

use App\Exceptions\ProductNotFoundException;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function __construct(protected HyperTechApiService $products) {}

    /**
     * Sepetteki tüm ürünler
     */
    public function items(): Collection
    {
        return CartItem::query()
            ->where('session_id', $this->sessionId())
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Sepete ürün ekle
     */
    public function addProduct(int $productId, int $quantity = 1): void
    {
        $product = $this->products->getProduct($productId);

        if (!$product) {
            throw new ProductNotFoundException('Ürün bilgisine ulaşılamadı.');
        }

        $quantity = max(1, $quantity);

        DB::transaction(function () use ($product, $quantity) {
            $item = CartItem::firstOrNew([
                'session_id' => $this->sessionId(),
                'product_id' => $product['productID'],
            ]);

            $item->fill($this->mapProduct($product));
            $item->quantity = $item->exists ? $item->quantity + $quantity : $quantity;
            $item->save();
        });
    }

    /**
     * Sepetteki ürünün miktarını güncelle
     */
    public function updateQuantity(int $itemId, int $quantity): void
    {
        $item = $this->findOwnedItem($itemId);
        $quantity = max(1, $quantity);

        $item->update(['quantity' => $quantity]);
    }

    /**
     * Sepetten ürün çıkar
     */
    public function removeItem(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);
        $item->delete();
    }

    /**
     * Sepet toplam tutar
     */
    public function total(): float
    {
        return $this->items()
            ->sum(fn(CartItem $item) => $item->total);
    }

    /**
     * Sepete ait ürünü bul
     */
    protected function findOwnedItem(int $itemId): CartItem
    {
        return CartItem::query()
            ->where('session_id', $this->sessionId())
            ->findOrFail($itemId);
    }

    /**
     * Oturum ID’si
     */
    protected function sessionId(): string
    {
        return session()->getId();
    }

    /**
     * API ürün verisini CartItem alanlarına map et
     */
    protected function mapProduct(array $product): array
    {
        return [
            'product_name' => $product['productName'] ?? 'Bilinmeyen Ürün',
            'product_slug' => $product['productSlug'] ?? null,
            'product_image' => data_get($product, 'productData.productMainImage'),
            'currency_code' => $product['currencyCode'] ?? 'TRY',
            'unit_price' => (float) data_get($product, 'salePrice', 0),
            'metadata' => [
                'productTypeID' => $product['productTypeID'] ?? null,
                'productCategoryID' => $product['productCategoryID'] ?? null,
                'productInfo' => data_get($product, 'productData.productInfo'),
            ],
        ];
    }
}
