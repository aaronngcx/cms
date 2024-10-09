<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Public Routes
Route::get('/', function () {
    return view('auth.login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // User Management Routes
    Route::prefix('admin/users')->middleware('role:Admin')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Post Management Routes
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/generate-outline', [PostController::class, 'generateOutline'])->name('posts.generate-outline');
        Route::post('/generate-seo', [PostController::class, 'generateSEO'])->name('posts.generate-seo');
        Route::delete('{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });
});

// Load authentication routes
require __DIR__ . '/auth.php';
