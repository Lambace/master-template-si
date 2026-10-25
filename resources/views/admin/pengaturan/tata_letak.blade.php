@extends('layouts.app')

@section('title', 'Pengaturan Tata Letak')
@section('page-title', 'Pengaturan Tata Letak')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

@php
    $pos    = \App\Models\Setting::get('layout_sidebar_position', 'left');
    $mode   = \App\Models\Setting::get('layout_sidebar_mode', 'full');
    $width  = \App\Models\Setting::get('layout_content_width', 'full');
    $corner = \App\Models\Setting::get('layout_corner', 'rounded');
    $scale  = \App\Models\Setting::get('layout_scale', 'normal');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Kolom Form --}}
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <form action="{{ url('/admin/pengaturan/tata-letak') }}" method="POST">
            @csrf

            <div class="mb-6">
                <h3 class="font-bold text-gray-800 mb-2">1. Posisi Sidebar</h3>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_sidebar_position" value="left" class="w-4 h-4" {{ $pos === 'left' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Kiri</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_sidebar_position" value="right" class="w-4 h-4" {{ $pos === 'right' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Kanan</span>
                    </label>
                    {{-- INI OPSI YANG HILANG SEBELUMNYA --}}
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_sidebar_position" value="top" class="w-4 h-4" {{ $pos === 'top' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm font-semibold text-blue-600">Atas (Menu Horisontal)</span>
                    </label>
                </div>
            </div>

            <div class="mb-6" id="mode-sidebar-container">
                <h3 class="font-bold text-gray-800 mb-2">2. Mode Sidebar</h3>
                <div class="flex space-x-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_sidebar_mode" value="full" class="w-4 h-4" {{ $mode === 'full' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Penuh (Ikon + Teks)</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_sidebar_mode" value="mini" class="w-4 h-4" {{ $mode === 'mini' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Mini (Ikon Saja)</span>
                    </label>
                </div>
                <p id="mode-warning" class="text-xs text-yellow-600 mt-1 hidden">* Mode Mini akan diabaikan otomatis jika posisi Sidebar di Atas.</p>
            </div>

            <div class="mb-6">
                <h3 class="font-bold text-gray-800 mb-2">3. Lebar Konten</h3>
                <div class="flex space-x-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_content_width" value="full" class="w-4 h-4" {{ $width === 'full' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Layar Penuh</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_content_width" value="boxed" class="w-4 h-4" {{ $width === 'boxed' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Boxed (Terpusat)</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="font-bold text-gray-800 mb-2">4. Gaya Sudut Kartu</h3>
                <div class="flex space-x-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_corner" value="rounded" class="w-4 h-4" {{ $corner === 'rounded' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Membulat (Modern)</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_corner" value="sharp" class="w-4 h-4" {{ $corner === 'sharp' ? 'checked' : '' }} onchange="refreshWire()">
                        <span class="text-sm">Siku (Formal)</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="font-bold text-gray-800 mb-2">5. Skala Tampilan</h3>
                <div class="flex space-x-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_scale" value="small" class="w-4 h-4" {{ $scale === 'small' ? 'checked' : '' }}>
                        <span class="text-sm">Kecil</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_scale" value="normal" class="w-4 h-4" {{ $scale === 'normal' ? 'checked' : '' }}>
                        <span class="text-sm">Normal</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="layout_scale" value="large" class="w-4 h-4" {{ $scale === 'large' ? 'checked' : '' }}>
                        <span class="text-sm">Besar</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm font-bold">
                    <i class="fas fa-save mr-2"></i>Simpan Tata Letak
                </button>
            </div>
        </form>
    </div>

    {{-- Kolom Pratinjau Wireframe --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-3">🔍 Pratinjau Miniatur</h3>
        <div id="wire" class="flex h-48 border border-gray-300 rounded overflow-hidden bg-gray-100">
            <div id="wire-side" class="bg-gray-700 w-16 p-2 space-y-2 transition-all duration-300">
                <div class="bg-white/20 h-3 rounded"></div>
                <div class="bg-white/20 h-3 rounded"></div>
                <div class="bg-white/20 h-3 rounded"></div>
            </div>
            <div class="flex-1 p-2 flex items-center justify-center">
                <div id="wire-content" class="bg-white border border-gray-200 h-full w-full rounded transition-all duration-300"></div>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">Miniatur ini memperagakan tata letak pilihan Anda secara langsung.</p>
    </div>
</div>

<script>
    function val(name) {
        const el = document.querySelector('input[name="' + name + '"]:checked');
        return el ? el.value : '';
    }

    function refreshWire() {
        const wire    = document.getElementById('wire');
        const side    = document.getElementById('wire-side');
        const content = document.getElementById('wire-content');
        const modeContainer = document.getElementById('mode-sidebar-container');
        const modeWarning = document.getElementById('mode-warning');

        const pos = val('layout_sidebar_position');

        // Logika Posisi Sidebar
        if (pos === 'top') {
            wire.style.flexDirection = 'column';
            side.style.width  = '100%';
            side.style.height = '2.5rem';
            side.style.display = 'flex';
            side.style.flexDirection = 'row';
            side.style.alignItems = 'center';
            side.style.padding = '0 1rem';
            
            // Sembunyikan pilihan mode mini jika posisi top
            modeWarning.classList.remove('hidden');
        } else {
            wire.style.flexDirection = (pos === 'right') ? 'row-reverse' : 'row';
            side.style.width  = (val('layout_sidebar_mode') === 'mini') ? '2rem' : '4rem';
            side.style.height = '100%';
            side.style.display = 'block';
            side.style.flexDirection = 'column';
            side.style.padding = '0.5rem';
            modeWarning.classList.add('hidden');
        }

        // Logika Lebar Konten
        if (val('layout_content_width') === 'boxed') {
            content.style.width  = '75%';
            content.style.margin = '0 auto';
        } else {
            content.style.width  = '100%';
            content.style.margin = '0';
        }

        // Logika Sudut
        const sharp = val('layout_corner') === 'sharp';
        wire.style.borderRadius    = sharp ? '0' : '0.5rem';
        content.style.borderRadius = sharp ? '0' : '0.25rem';
    }

    // Jalankan saat halaman pertama kali dimuat
    refreshWire();
</script>
@endsection