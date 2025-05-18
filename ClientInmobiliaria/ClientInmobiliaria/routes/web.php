<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;


use App\Http\Controllers\Auth\RegisteredUserController;

// Página principal redirige a propiedades
Route::get('/', function () {
    return redirect()->route('properties.index');
});

// Autenticación vía API
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


// Rutas protegidas por sesión con token API
Route::middleware('auth.session')->group(function () {
    Route::post('/reviews/{propertyId}', [ReviewController::class, 'store'])->name('reviews.store');

    // Si vas a usar perfil (opcional, depende de si integras con backend API)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Propiedades públicas
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');
Route::post('/properties/{id}/buy', [PropertyController::class, 'buy'])->name('properties.buy');
Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');

Route::get('/cart', [CartController::class, 'getCart'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::post('/cart/purchase/{id}', [PropertyController::class, 'purchase'])->name('cart.purchase');



// Comentarios visibles sin login
Route::get('/reviews/{propertyId}', [ReviewController::class, 'index'])->name('reviews.index');

// Dashboard protegido por sesión
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
