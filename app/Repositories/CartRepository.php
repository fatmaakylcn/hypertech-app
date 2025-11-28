<?php

namespace App\Repositories;

use App\Services\CartService;
use Illuminate\Support\Collection;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(protected CartService $service) {}

    public function all():Collection
    {
        return $this->service->items();
    }

    public function total(): float
    {
        return $this->service->total();
    }

    public function addProduct(int $productId, int $quantity = 1): void
    {
        $this->service->addProduct($productId, $quantity);
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        $this->service->updateQuantity($itemId, $quantity);
    }

    public function removeItem(int $itemId): void
    {
        $this->service->removeItem($itemId);
    }
}
