<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route Publik
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Publik (Login/Logout tetap sama)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruangan Khusus ADMIN
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Ruangan Khusus PIMPINAN
Route::middleware(['auth', 'role:Pimpinan'])->group(function () {
    Route::get('/pimpinan/dashboard', function () {
        return view('pimpinan.dashboard');
    })->name('pimpinan.dashboard');
});