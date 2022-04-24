<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', [ProductsController::class, 'categories']);

Route::get('/products', [ProductsController::class, 'products']);

Route::get('/product/{slug}', [ProductsController::class, 'product']);

Route::get('/products/{slug}', [ProductsController::class, 'CategoryProducts']);

Route::get('/offers', [ProductsController::class, 'offers']);

Route::get('/deals', [ProductsController::class, 'deals']);

Route::post('/get-insta-users', [ProductsController::class, 'getInstaUsers']);

Route::get('/india-talents-crawler', [ProductsController::class, 'indiaTalentsCrawler']);
