<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;

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

// public route /api for initial testing
Route::get('/', function () {
    return response()->json(['message' => 'Your app is connected to the server']);
});

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

    // Product routes
    Route::get('products/deleted', [ProductController::class, 'getSoftDelete'])->name('products.softdelete');
    Route::put('products/restore/{product}', [ProductController::class, 'restoreSoftDelete'])->name('products.restore');
    Route::resource('products', ProductController::class);

    // Category routes
    Route::get('categories/deleted', [CategoryController::class, 'trash'])->name('categories.softdelete');
    Route::put('categories/restore/{category}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('categories', CategoryController::class);

    // Brand routes
    Route::get('brands/deleted', [BrandController::class, 'trash'])->name('brands.softdelete');
    Route::put('brands/restore/{brand}', [BrandController::class, 'restore'])->name('brands.restore');
    Route::resource('brands', BrandController::class);
});
