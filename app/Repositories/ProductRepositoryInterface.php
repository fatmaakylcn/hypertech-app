<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function all(int $page = 1, int $pageSize = 12): array;
    public function find(int $id): ?array;
}

