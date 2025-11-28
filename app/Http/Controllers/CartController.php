<?php

namespace App\Http\Controllers;

use App\Exceptions\HyperTechApiException;
use App\Exceptions\ProductNotFoundException;
use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Repositories\CartRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartRepositoryInterface $cart
    ) {
    }

    /**
     * Sepeti görüntüle
     */
    public function show(): View
    {
        return view('cart.show', [
            'items' => $this->cart->all(),
            'total' => $this->cart->total(),
            'cacheTtl' => config('hypertech.cache_ttl'),
        ]);
    }

    /**
     * Sepete ürün ekle
     */
    public function store(CartStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $this->cart->addProduct((int) $data['product_id'], (int) ($data['quantity'] ?? 1));
            return back()->with('status', 'Ürün sepete eklendi.');
        } catch (ProductNotFoundException|HyperTechApiException $exception) {
            return back()->withErrors([
                'cart' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Sepetteki ürün miktarını güncelle
     */
    public function update(CartUpdateRequest $request, int $itemId): RedirectResponse
    {
        $data = $request->validated();

        try {
            $this->cart->updateQuantity($itemId, (int) $data['quantity']);
            return back()->with('status', 'Sepet güncellendi.');
        } catch (\Exception $exception) {
            return back()->withErrors(['cart' => $exception->getMessage()]);
        }
    }

    /**
     * Sepetten ürün sil
     */
    public function destroy(int $itemId): RedirectResponse
    {
        try {
            $this->cart->removeItem($itemId);
            return back()->with('status', 'Ürün sepetten çıkarıldı.');
        } catch (\Exception $exception) {
            return back()->withErrors(['cart' => $exception->getMessage()]);
        }
    }
}
