<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Auth\SessionController;

use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DashboardController;

Route::post('login', [ApiAuthController::class, 'login']);
Route::post('register', [ApiAuthController::class, 'register']);

Route::get('properties', [PropertyController::class, 'index']);
Route::get('properties/{id}', [PropertyController::class, 'show']);
Route::get('properties/{id}/reviews', [ReviewController::class, 'index']);

Route::post('/save-token', [SessionController::class, 'saveToken'])->name('save_token');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ApiAuthController::class, 'logout']);

    Route::get('dashboard', [DashboardController::class, 'index']); // RUTA PÚBLICA


    Route::post('properties', [PropertyController::class, 'store']);
    Route::put('properties/{id}', [PropertyController::class, 'update']);
    Route::delete('properties/{id}', [PropertyController::class, 'destroy']);
    Route::post('properties/{id}/buy', [PropertyController::class, 'buy']);
    Route::post('properties/{id}/purchase', [PropertyController::class, 'purchase']);

    Route::post('properties/{id}/reviews', [ReviewController::class, 'store']);

    Route::get('profile/manage', [ProfileController::class, 'manage']);
    Route::patch('profile', [ProfileController::class, 'update']);
    Route::delete('profile', [ProfileController::class, 'destroy']);

    Route::post('orders', [OrderController::class, 'placeOrder']);
    Route::get('orders', [OrderController::class, 'getOrderHistory']);

    Route::get('cart', [CartController::class, 'getCart']);               // Obtener carrito actual
    Route::post('cart/{propertyId}', [CartController::class, 'addToCart']);           // Añadir producto al carrito
    Route::delete('cart/{id}', [CartController::class, 'removeFromCart']);
});
