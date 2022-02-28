<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

// Route::post('/user/registration', [AuthController::class, 'store']);
// Route::post('/user/login', [AuthController::class, 'login']);

Route::name('user.')->group(function(){
    Route::post('/user/registration', [AuthController::class, 'store'])->name('registration');
    Route::post('/user/login', [AuthController::class, 'login'])->name('login');
});

Route::apiResources([
    'user' => UserController::class,
    'brand' => BrandController::class,
    'category' => CategoryController::class,
    'product' => ProductController::class,
]);
