<?php

namespace App\Repositories;

use App\Services\HyperTechApiService;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected HyperTechApiService $apiService) {}

    public function all(int $page = 1, int $pageSize = 12): array
    {
        return $this->apiService->listProducts($page, $pageSize)['items'] ?? [];
    }

    public function find(int $id): ?array
    {
        return $this->apiService->getProduct($id);
    }
}
