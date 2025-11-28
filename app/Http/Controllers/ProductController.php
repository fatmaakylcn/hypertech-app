<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $products
    ) {
    }

    public function index(Request $request)
    {
        $page = max(1, (int) $request->integer('page', 1));
        $pageSize = (int) config('hypertech.default_page_size', 12);
        $error = null;
        $items = collect();

        try {
            $itemsArray = $this->products->all($page, $pageSize);
            $items = collect($itemsArray);
        } catch (\Exception $exception) {
            $error = __('Ürünler şu anda yüklenemiyor. Lütfen daha sonra tekrar deneyin.');
            Log::error('Unable to list products', [
                'message' => $exception->getMessage(),
            ]);
        }

        $paginator = $this->buildPaginator($items, $page, $pageSize, $request);

        return view('products.index', [
            'products' => $paginator,
            'error' => $error,
            'cacheTtl' => config('hypertech.cache_ttl'),
        ]);
    }

    public function show(int $id)
    {
        $error = null;
        $product = null;

        try {
            $product = $this->products->find($id);
        } catch (\Exception $exception) {
            $error = __('Ürün detayları yüklenemiyor. Lütfen daha sonra tekrar deneyin.');
            Log::error('Unable to fetch product detail', [
                'message' => $exception->getMessage(),
                'product_id' => $id,
            ]);
        }

        return view('products.show', compact('product', 'error'));
    }

    protected function buildPaginator(Collection $items, int $page, int $perPage, Request $request): LengthAwarePaginator
    {
        $total = $this->guessTotal($items->count(), $page, $perPage);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    protected function guessTotal(int $currentCount, int $page, int $perPage): int
    {
        $hasMore = $currentCount === $perPage;

        return ($page - 1) * $perPage + $currentCount + ($hasMore ? $perPage : 0);
    }
}
