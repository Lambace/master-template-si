@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')
@section('page-title', 'Dashboard Pimpinan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Card Hasil SPK -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Hasil Perankingan SPK</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span>Alternatif A</span>
                <span class="font-bold text-green-600">85.5</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span>Alternatif B</span>
                <span class="font-bold text-blue-600">78.2</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span>Alternatif C</span>
                <span class="font-bold text-yellow-600">72.8</span>
            </div>
        </div>
    </div>
    
    <!-- Card Statistik -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Statistik Sistem</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Kriteria</span>
                <span class="font-bold">5</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Alternatif</span>
                <span class="font-bold">10</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Perhitungan Terakhir</span>
                <span class="font-bold">Hari ini</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold mb-4">Selamat Datang, Pimpinan!</h3>
    <p class="text-gray-600">
        Anda dapat melihat hasil perhitungan SPK dan laporan sistem. 
        Untuk konfigurasi lebih lanjut, silakan hubungi administrator.
    </p>
</div>
@endsection