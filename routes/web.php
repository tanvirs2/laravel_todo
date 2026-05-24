<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect('/todos'));

    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::put('/todos/{todo}', [TodoController::class, 'update']);
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);

    Route::post('/categories', [TodoController::class, 'storeCategory']);
    Route::delete('/categories/{category}', [TodoController::class, 'destroyCategory']);

    Route::get('/report', [TodoController::class, 'report']);
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
