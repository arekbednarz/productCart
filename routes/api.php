<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('products', ProductController::class);

Route::post('carts', [CartController::class, 'create'])->name('cart.create');
Route::post('carts/{reference}/{product_id}', [CartController::class, 'addProduct'])->name('cart.product.add');
Route::delete('carts/{reference}/{product_id}', [CartController::class, 'removeProduct'])->name('cart.product.remove');
Route::get('carts/{reference}', [CartController::class, 'list'])->name('cart.list');
