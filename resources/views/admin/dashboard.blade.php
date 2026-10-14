@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total User</p>
                <p class="text-3xl font-bold text-gray-800">2</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Metode SPK</p>
                <p class="text-3xl font-bold text-gray-800">SAW</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-calculator text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Status Sistem</p>
                <p class="text-3xl font-bold text-green-600">Aktif</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold mb-4">Selamat Datang, Admin!</h3>
    <p class="text-gray-600">
        Anda memiliki akses penuh ke sistem. Gunakan menu di sidebar untuk mengelola pengguna, 
        konfigurasi metode SPK, dan pengaturan sistem lainnya.
    </p>
</div>
@endsection