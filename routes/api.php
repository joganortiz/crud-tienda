<?php

use App\Http\Controllers\CategoryController;
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
 * Peticiones a la de crud Categorias
 */
// Route::post('/categoria/add', [CategoryController::class, 'addCategory']);

// Route::put('/categoria/edit/{id}', function () {
//     echo "editar";
//     exit;
// });

Route::apiResource('category', CategoryController::class);
