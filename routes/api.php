<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Models\Product;
use App\Models\ProductInformation;

use \App\Http\Controllers\Api\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::get('products', function (Request $request) {
    return Product::all();
});
*/
Route::apiResource('products', ProductController::class)->only(['index', 'show']);

/*
Route::get('bookables/{bookable}/availability', 'Api\BookableAvailabilityController')
    ->name('bookables.availability.show');

Route::get('information', function (Request $request) {
    return ProductInformation::all();
});
*/
