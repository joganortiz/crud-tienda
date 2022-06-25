<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\storeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Peticiones a Categorias
 */
Route::apiResource('category', CategoryController::class);

/**
 * Peticiones a productos
 */
Route::apiResource('product', ProductController::class);

/**
 * Peticiones tienda
*/
Route::post('agregarCarrito', [storeController::class, 'store']);
Route::get('ListCart', [storeController::class, 'index']);
Route::put('actualizarCantidad/{id}', [storeController::class, 'update']);
Route::delete('eliminarProductoCarro/{id}', [storeController::class, 'destroy']);
Route::post('venderProductos', [storeController::class, 'sellProducts']);