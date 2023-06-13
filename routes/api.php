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
    Route::post('me', [AuthController::class, 'me']);

});

Route::get('/', [\App\Http\Controllers\API\RoleController::class, 'index']);
//Route::get('/users', [UserController::class, 'index']);

//admin
Route::apiResources([
    'users' => UserController::class
]);

Route::apiResources([
    'categories' => CategoryController::class
],['middleware' => ['auth', 'admin']]);

//provider
Route::apiResources([
    'provider/products' => ProviderController::class,
],['middleware' => ['auth', 'provider']]);

//client
//Route::apiResources([
//    'products' => ProductClientController::class
//],['middleware' => ['auth', 'client']]);
Route::group([
    'prefix' => 'products',
    'middleware' => ['auth','client'],
], function ($router) {

    Route::get('', [ProductClientController::class, 'index']);
    Route::get('/{id}', [ProductClientController::class, 'show']);
    Route::get('/{id}/select', [ProductClientController::class, 'select']);
});


Route::apiResources([
    'selected' => SelectedController::class
],['middleware' => ['auth', 'client']]);



Route::fallback(function (){
    return response() ->json([
        'message' => 'Page Not Found'], 404);
});
