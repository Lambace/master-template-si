@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')

@section('content')

{{-- 1. BANNER UNGGULAN: Tombol Download ZIP --}}
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 text-white mb-6">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-bold mb-1">🚀 Aplikasi Anda telah Siap!</h2>
            <p class="text-blue-100 text-sm">Template SPK Anda sudah lengkap. Download source code sekarang.</p>
        </div>
        <a href="{{ route('admin.generator.download') }}" 
           class="inline-flex items-center bg-white text-blue-700 font-bold px-6 py-3 rounded-lg hover:bg-blue-50 transition shadow-md transform hover:scale-105">
            <i class="fas fa-file-archive text-xl mr-2"></i>
            Download Source Code (.zip)
        </a>
    </div>
    <p class="text-xs text-blue-200 mt-4 border-t border-blue-500 pt-2">
        <i class="fas fa-info-circle mr-1"></i> File berisi: app, routes, views, migrations, seeders, dan composer.json. (Folder vendor & node_modules dikecualikan agar file ringan).
    </p>
</div>

{{-- 2. STATISTIK SISTEM --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Card 1: Metode -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Metode Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">SAW</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-calculator text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Status -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Status Sistem</p>
                <p class="text-3xl font-bold text-green-600 mt-1">Aktif</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 3: Modul -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Modul Tersedia</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">4/4</p>
                <p class="text-xs text-gray-400">Kriteria, Alternatif, Nilai, Laporan</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-cubes text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- 3. AKSI CEPAT (Quick Actions) --}}
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">⚡ Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <a href="{{ url('/admin/kriteria') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition border border-gray-200 group">
            <i class="fas fa-list-ul text-2xl mr-3 text-blue-500 group-hover:scale-110 transition"></i>
            <div>
                <p class="font-bold">Kelola Kriteria</p>
                <p class="text-xs text-gray-500">Atur bobot & tipe</p>
            </div>
        </a>
        
        <a href="{{ url('/admin/alternatif') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 hover:text-green-700 transition border border-gray-200 group">
            <i class="fas fa-users text-2xl mr-3 text-green-500 group-hover:scale-110 transition"></i>
            <div>
                <p class="font-bold">Kelola Alternatif</p>
                <p class="text-xs text-gray-500">Tambah calon pilihan</p>
            </div>
        </a>
        
        <a href="{{ url('/admin/penilaian') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 hover:text-yellow-700 transition border border-gray-200 group">
            <i class="fas fa-table text-2xl mr-3 text-yellow-500 group-hover:scale-110 transition"></i>
            <div>
                <p class="font-bold">Input Penilaian</p>
                <p class="text-xs text-gray-500">Isi matriks keputusan</p>
            </div>
        </a>
        
        <a href="{{ route('admin.spk.hasil') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-purple-50 hover:text-purple-700 transition border border-gray-200 group">
            <i class="fas fa-file-pdf text-2xl mr-3 text-purple-500 group-hover:scale-110 transition"></i>
            <div>
                <p class="font-bold">Lihat Laporan</p>
                <p class="text-xs text-gray-500">Cetak hasil & ranking</p>
            </div>
        </a>
        
    </div>
</div>

@endsection