<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function all(): Collection;
    public function total(): float;
    public function addProduct(int $productId, int $quantity = 1): void;
    public function updateQuantity(int $itemId, int $quantity): void;
    public function removeItem(int $itemId): void;
}
