<?php

namespace App\Services;

use App\Exceptions\HyperTechApiException;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Throwable;

class HyperTechApiService
{
    public function __construct(
        protected HttpFactory $http,
        protected CacheRepository $cache
    ) {
    }

    public function listProducts(int $page, int $pageSize): array
    {
        $cacheKey = $this->cacheKey('products', "page_{$page}_size_{$pageSize}");

        return $this->cache->remember($cacheKey, $this->cacheTtl(), function () use ($page, $pageSize) {
            $query = [
                'page' => $page,
                'pageSize' => $pageSize,
            ];

            $response = $this->sendRequest(fn (PendingRequest $client) => $client
                ->withQueryParameters($query)
                ->post('/Products/List'));

            return [
                'items' => $response['data'] ?? [],
                'success' => $response['success'] ?? false,
                'message' => $response['message'] ?? null,
            ];
        });
    }

    public function getProduct(int $productId): ?array
    {
        $cacheKey = $this->cacheKey('product', $productId);

        return $this->cache->remember($cacheKey, $this->cacheTtl(), function () use ($productId) {
            $client = $this->client();

            try {
                $payload = ['productID' => $productId];
                $response = $client->post('/Products/Detail', $payload);

                if ($response->successful()) {
                    return $response->json()['data'] ?? null;
                }
            } catch (RequestException $e) {
                Log::warning('HyperTech API detail (payload) attempt failed', [
                    'code' => optional($e->response)->status(),
                    'message' => optional($e->response)->body(),
                ]);
            }

            try {
                $response = $client->post("/Products/Detail/{$productId}");

                if ($response->successful()) {
                    return $response->json()['data'] ?? null;
                }
            } catch (RequestException $e) {
                Log::warning('HyperTech API detail (path) attempt failed', [
                    'code' => optional($e->response)->status(),
                    'message' => optional($e->response)->body(),
                ]);
            }
            $response = $this->sendRequest(fn (PendingRequest $client) => $client->get("/Products/Detail/{$productId}"));

            return $response['data'] ?? null;
        });
    }

    protected function sendRequest(callable $callback): array
    {
        $client = $this->client();

        try {
            $response = $callback($client)->throw();

            return $response->json();
        } catch (RequestException $exception) {
            Log::warning('HyperTech API bir hatayla yanıt verdi', [
                'code' => optional($exception->response)->status(),
                'message' => optional($exception->response)->body(),
            ]);

            throw new HyperTechApiException(
                'HyperTech API bir hatayla yanıt verdi',
                previous: $exception
            );
        } catch (Throwable $exception) {
            Log::error('HyperTech API isteği başarısız oldu', [
                'message' => $exception->getMessage(),
            ]);

            throw new HyperTechApiException(
                "HyperTech API'ye şu anda ulaşılamıyor.",
                previous: $exception
            );
        }
    }
protected function client()
{
    $baseUrl = rtrim(config('hypertech.base_url'), '/');
    $apiKey = config('hypertech.api_key');
    $token = config('hypertech.api_token');

    return $this->http->baseUrl($baseUrl)
        ->withHeaders([
            'Accept' => 'application/json',
            'ApiKey' => $apiKey,
            'Authorization' => sprintf('Bearer %s', $token),
        ])
        ->timeout(10)
        ->retry(2, 200);
}

    protected function cacheKey(string $prefix, string|int $suffix): string
    {
        return sprintf('hypertech.%s.%s', $prefix, $suffix);
    }

    protected function cacheTtl(): int
    {
        return (int) config('hypertech.cache_ttl', 300);
    }
}

