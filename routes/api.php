<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
// Obtiene todos los productos
Route::get('/products/{page?}', 'App\Http\Controllers\ECommerceController@index');
Route::get('/some', 'App\Http\Controllers\ECommerceController@getSomeProducts');
Route::post('/products/{page?}', 'App\Http\Controllers\ECommerceController@getProductsFilter');
//Route::get('/products', 'App\Http\Controllers\ECommerceController@getProducts');
Route::get('/search/{page?}', 'App\Http\Controllers\ECommerceController@search');
