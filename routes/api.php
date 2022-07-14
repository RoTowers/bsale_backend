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

// Obtiene todos los productos paginados
Route::get('/products/{page?}', 'App\Http\Controllers\ECommerceController@index');
// Obtiene algunos de los productos con descuentos
Route::get('/some', 'App\Http\Controllers\ECommerceController@getSomeProducts');
// Obtiene los productos en base a los filtros y paginados
Route::post('/products/{page?}', 'App\Http\Controllers\ECommerceController@getProductsFilter');
// Obtiene los productos paginados y en base a la busqueda
Route::get('/search/{page?}', 'App\Http\Controllers\ECommerceController@search');
