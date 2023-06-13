<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
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
Route::apiResources([
    'users' => UserController::class
], );
//Route::get('/categories', [\App\Http\Controllers\API\CategoryController::class, 'index']);

//Route::apiResources([
//    'categories' => CategoryController::class
//],['middleware' => 'auth']);

Route::apiResources([
    'categories' => CategoryController::class
],['middleware' => ['auth', 'admin']]);

Route::apiResources([
    'products' => ProductController::class
]);
Route::apiResources([
    'selected' => SelectedController::class
]);

Route::apiResources([
    'provider/products' => ProviderController::class,
],['middleware' => ['auth', 'provider']]);


Route::fallback(function (){
    return response() ->json([
        'message' => 'Page Not Found'], 404);
});
