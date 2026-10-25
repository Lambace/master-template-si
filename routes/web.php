<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\SpkController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\Admin\AlternatifController; 
use App\Http\Controllers\Admin\GeneratorController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;


// Route akar: arahkan otomatis sesuai status login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect('/admin/dashboard')
            : redirect('/pimpinan/dashboard');
    }
    return redirect('/login');
});
/*
|--------------------------------------------------------------------------
| Route Publik
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Route Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/spk/hasil', [SpkController::class, 'hasil'])->name('admin.spk.hasil');

    Route::resource('/admin/kriteria', KriteriaController::class);

    Route::resource('/admin/alternatif', AlternatifController::class);

    // --- PERBAIKAN DI SINI: Pastikan GET dan POST ada di dalam grup ini ---
    Route::get('/admin/penilaian', [PenilaianController::class, 'index']);
    Route::post('/admin/penilaian', [PenilaianController::class, 'store']);
    Route::get('/admin/generator/download', [GeneratorController::class, 'downloadZip'])->name('admin.generator.download');
    
    Route::resource('/admin/users', UserController::class);
    Route::get('/admin/pengaturan/tema', [ThemeController::class, 'index']);
    Route::post('/admin/pengaturan/tema', [ThemeController::class, 'update']);

    Route::get('/admin/pengaturan/tata-letak', [LayoutController::class, 'index']);
    Route::post('/admin/pengaturan/tata-letak', [LayoutController::class, 'update']);

    Route::get('/admin/pengaturan/profile', [ProfileController::class, 'index']);
    Route::post('/admin/pengaturan/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/admin/pengaturan/branding/update', [ProfileController::class, 'updateBranding']);
});


/*
|--------------------------------------------------------------------------
| Route Pimpinan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Pimpinan'])->group(function () {
    
    Route::get('/pimpinan/dashboard', function () {
        return view('pimpinan.dashboard');
    })->name('pimpinan.dashboard');
});