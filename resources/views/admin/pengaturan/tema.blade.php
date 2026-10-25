@extends('layouts.app')

@section('title', 'Pengaturan Tema')
@section('page-title', 'Pengaturan Tema Tampilan')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

@php
    $mode   = \App\Models\Setting::get('theme_mode', 'solid');
    $c1     = \App\Models\Setting::get('theme_primary', '#1f2937');
    $c2     = \App\Models\Setting::get('theme_secondary', '#111827');
    $accent = \App\Models\Setting::get('theme_accent', '#2563eb');

    $presets = [
        ['nama' => 'Slate Gelap',  'c1' => '#1f2937', 'c2' => '#111827'],
        ['nama' => 'Biru Klasik',  'c1' => '#1e3a8a', 'c2' => '#1d4ed8'],
        ['nama' => 'Hijau Emerald','c1' => '#064e3b', 'c2' => '#059669'],
        ['nama' => 'Merah Marun',  'c1' => '#7f1d1d', 'c2' => '#dc2626'],
        ['nama' => 'Ungu Royal',   'c1' => '#4c1d95', 'c2' => '#7c3aed'],
        ['nama' => 'Teal Ocean',   'c1' => '#134e4a', 'c2' => '#0d9488'],
        ['nama' => 'Oranye Senja', 'c1' => '#7c2d12', 'c2' => '#ea580c'],
        ['nama' => 'Pink Fancy',   'c1' => '#831843', 'c2' => '#db2777'],
    ];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Kolom Form --}}
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <form action="{{ url('/admin/pengaturan/tema') }}" method="POST">
            @csrf

            <h3 class="font-bold text-gray-800 mb-3">1. Pilih Mode Warna</h3>
            <div class="flex space-x-6 mb-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="theme_mode" value="solid" class="w-4 h-4" {{ $mode === 'solid' ? 'checked' : '' }} onchange="refreshPreview()">
                    <span class="text-sm">Warna Statis (Solid)</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="theme_mode" value="gradient" class="w-4 h-4" {{ $mode === 'gradient' ? 'checked' : '' }} onchange="refreshPreview()">
                    <span class="text-sm">Warna Gradien (Gabungan 2 Warna)</span>
                </label>
            </div>

            <h3 class="font-bold text-gray-800 mb-3">2. Tema Instan (Preset)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                @foreach ($presets as $p)
                    <button type="button" onclick="setColors('{{ $p['c1'] }}', '{{ $p['c2'] }}')"
                        class="h-12 rounded-lg text-white text-xs font-bold shadow hover:scale-105 transition"
                        style="background: linear-gradient(135deg, {{ $p['c1'] }}, {{ $p['c2'] }});">
                        {{ $p['nama'] }}
                    </button>
                @endforeach
            </div>

            <h3 class="font-bold text-gray-800 mb-3">3. Kustomisasi Manual (Color Picker)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna Utama</label>
                    <input type="color" id="c1" name="theme_primary" value="{{ $c1 }}"
                        class="w-full h-10 cursor-pointer border border-gray-300 rounded" onchange="refreshPreview()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna Kedua (Gradien)</label>
                    <input type="color" id="c2" name="theme_secondary" value="{{ $c2 }}"
                        class="w-full h-10 cursor-pointer border border-gray-300 rounded" onchange="refreshPreview()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna Aksen (Tombol)</label>
                    <input type="color" id="c3" name="theme_accent" value="{{ $accent }}"
                        class="w-full h-10 cursor-pointer border border-gray-300 rounded" onchange="refreshPreview()">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 text-white rounded-lg shadow-sm font-bold" style="background-color: {{ $accent }};">
                    <i class="fas fa-save mr-2"></i>Simpan Tema
                </button>
            </div>
        </form>
    </div>

    {{-- Kolom Pratinjau --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-3">🔍 Pratinjau Langsung</h3>
        <div id="preview-box" class="rounded-lg p-4 text-white mb-4" style="min-height: 160px;">
            <p class="font-bold">Sidebar Aplikasi</p>
            <p class="text-xs opacity-75 mb-3">Beginilah warna sidebar Anda nanti</p>
            <div class="bg-white/10 rounded px-3 py-2 text-sm">Menu Aktif</div>
        </div>
        <button id="preview-btn" type="button" class="w-full py-2 rounded-lg text-white font-bold text-sm">Contoh Tombol Aksen</button>
        <p class="text-xs text-gray-500 mt-3">Perubahan warna langsung terlihat di sini sebelum disimpan.</p>
    </div>
</div>

<script>
    function setColors(c1, c2) {
        document.getElementById('c1').value = c1;
        document.getElementById('c2').value = c2;
        refreshPreview();
    }

    function refreshPreview() {
        const mode = document.querySelector('input[name="theme_mode"]:checked').value;
        const c1 = document.getElementById('c1').value;
        const c2 = document.getElementById('c2').value;
        const c3 = document.getElementById('c3').value;

        const box = document.getElementById('preview-box');
        box.style.background = (mode === 'gradient')
            ? 'linear-gradient(135deg, ' + c1 + ', ' + c2 + ')'
            : c1;
        document.getElementById('preview-btn').style.backgroundColor = c3;
    }

    refreshPreview();
</script>
@endsection