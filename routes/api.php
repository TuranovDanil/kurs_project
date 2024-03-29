<?php

use App\Http\Controllers\API\Admin\AuthController;
use App\Http\Controllers\API\Admin\CategoryController;
use App\Http\Controllers\API\Admin\UserController;
use App\Http\Controllers\API\Client\ProductClientController;
use App\Http\Controllers\API\Client\SelectedController;
use App\Http\Controllers\API\Provider\ProviderController;
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

Route::group([

    'middleware' => 'api',
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register_provider', [AuthController::class, 'registerProvider']);
    Route::post('register_client', [AuthController::class, 'registerClient']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::patch('me', [AuthController::class, 'update']);
    Route::delete('me', [AuthController::class, 'destroy']);

});


//admin
Route::apiResources([
    'users' => UserController::class
],['middleware' => ['auth', 'admin']]);

Route::group([
   'prefix' => 'categories',
   'middleware' => ['auth'],
], function ($router){
    Route::get('', [CategoryController::class, 'index'])->middleware('banned');
    Route::post('', [CategoryController::class, 'store'])->middleware('admin');
    Route::get('/{category}', [CategoryController::class, 'show'] )->middleware('admin');
    Route::patch('/{category}', [CategoryController::class, 'update'] )->middleware('admin');
    Route::delete('/{category}', [CategoryController::class, 'destroy'] )->middleware('admin');
});


//provider
Route::apiResources([
    'provider/products' => ProviderController::class,
],['middleware' => ['auth', 'provider', 'banned']]);


//client
Route::group([
    'prefix' => 'products',
    'middleware' => ['auth','client', 'banned'],
], function ($router) {

    Route::get('', [ProductClientController::class, 'index']);
    Route::get('/{product}', [ProductClientController::class, 'show']);
    Route::get('/{product}/select', [ProductClientController::class, 'select']);
});

Route::apiResources([
    'selected' => SelectedController::class
],['middleware' => ['auth', 'client', 'banned']]);


//not found
Route::fallback(function (){
    return response() ->json([
        'message' => 'Page Not Found'], 404);
});
