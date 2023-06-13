<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductClientController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\SelectedController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
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

Route::get('/', [\App\Http\Controllers\API\RoleController::class, 'index']);
//Route::get('/users', [UserController::class, 'index']);

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
