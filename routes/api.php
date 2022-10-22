<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Product;
use App\Models\ProductInformation;

use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\ProductInformationController;
use \App\Http\Controllers\Api\ProductIdController;
use \App\Http\Controllers\Api\CategoryController;

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
Route::apiResource('products', ProductController::class)->only(['index', 'show']);  // SORT SHOULDN'T BE SERVER SIDE?


Route::apiResource('information', ProductInformationController::class)->only(['index', 'show']);

Route::get('category/{category}', function ($category, Request $request) {
    // return ProductInformation::with(['product'])->where('fit', 'like', $category)->get();

    return Product::join('product_information', 'products.id', '=', 'product_id')
          ->where($category, 'like', '%'.$request->query()['filter'].'%')->get();

});

Route::apiResource('categories', CategoryController::class)->only(['index']);

Route::apiResource('categories/id', ProductIdController::class)->only(['index']);


/*
Route::get('categories', function (Request $request) {
    // return ProductInformation::with(['product'])->where('fit', 'like', $category)->get();

    return ProductInformation::select('fit', DB::raw('count(fit) AS total'))->groupBy('fit')->orderBy('fit')->get();
    // SELECT fit, count(fit) AS Total FROM product_information GROUP BY fit;
    select `colour`, `brand`, `product_information`.`fit`, `product_information`.`rise`, `product_information`.`terrain` from `products` inner join `product_information` on `products`.`id` = `product_id`
});
*/
/*
Route::get('bookables/{bookable}/availability', 'Api\BookableAvailabilityController')
    ->name('bookables.availability.show');

Route::get('information', function (Request $request) {
    return ProductInformation::all();
});
*/
