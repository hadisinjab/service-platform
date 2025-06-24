<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('provider')) {
        return redirect()->route('provider.dashboard');
    } elseif ($user->hasRole('client')) {
        return redirect()->route('client.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/providers', [AdminController::class, 'providers'])->name('providers');
    Route::get('/clients', [AdminController::class, 'clients'])->name('clients');
});

// Provider Routes
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [ProviderController::class, 'dashboard'])->name('dashboard');
    Route::get('/services', [ProviderController::class, 'services'])->name('services');
    Route::get('/orders', [ProviderController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [ProviderController::class, 'orderDetails'])->name('order.details');
    Route::get('/profile', [ProviderController::class, 'profile'])->name('profile');
});

// Client Routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/services', [ClientController::class, 'services'])->name('services');
    Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [ClientController::class, 'orderDetails'])->name('order.details');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
