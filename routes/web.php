<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\SpkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Publik (Login & Logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Route Khusus Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Halaman Laporan Perhitungan SAW (Fitur Utama)
    Route::get('/admin/spk/hasil', [SpkController::class, 'hasil'])->name('admin.spk.hasil');
});

/*
|--------------------------------------------------------------------------
| Route Khusus Pimpinan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Pimpinan'])->group(function () {
    
    // Dashboard Pimpinan
    Route::get('/pimpinan/dashboard', function () {
        return view('pimpinan.dashboard');
    })->name('pimpinan.dashboard');
});