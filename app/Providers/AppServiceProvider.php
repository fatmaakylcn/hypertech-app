<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
