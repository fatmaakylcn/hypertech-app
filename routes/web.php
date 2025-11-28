<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
