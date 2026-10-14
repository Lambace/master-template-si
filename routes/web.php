<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route Publik
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Terlindungi (wajib login)
Route::get('/dashboard', function () {
    $user = auth()->user();

    return "Selamat datang, {$user->name}! Role Anda: {$user->role->nama_role}";
})->middleware('auth')->name('dashboard');