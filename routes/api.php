<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SelectedController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [\App\Http\Controllers\API\RoleController::class, 'index']);
//Route::get('/users', [UserController::class, 'index']);
Route::apiResources([
    'users' => UserController::class
]);
Route::get('/categories', [\App\Http\Controllers\API\CategoryController::class, 'index']);
Route::apiResources([
    'products' => ProductController::class
]);
Route::apiResources([
    'selected' => SelectedController::class
]);
