@extends('layouts.app')

@section('title', 'Profil & Branding')
@section('page-title', 'Profil & Branding Aplikasi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    {{-- KARTU 1: PROFIL USER --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-edit mr-2 text-blue-500"></i>Profil Saya</h3>
        
        @if (session('success_profile'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">{{ session('success_profile') }}</div>
        @endif

        <form action="{{ url('/admin/pengaturan/profile/update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak diubah</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-bold transition">Simpan Profil</button>
        </form>
    </div>

    {{-- KARTU 2: BRANDING APLIKASI --}}
    <div class="bg-white rounded-lg shadow-sm p-6 border-2 border-purple-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-building mr-2 text-purple-500"></i>Branding & Kop Laporan</h3>
        <p class="text-xs text-gray-500 mb-4">Data ini akan otomatis digunakan pada nama aplikasi di sidebar dan <strong>Kop Laporan PDF (Bab 4)</strong>.</p>
        
        @if (session('success_branding'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">{{ session('success_branding') }}</div>
        @endif

        <form action="{{ url('/admin/pengaturan/branding/update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi <span class="text-red-500">*</span></label>
                <input type="text" name="app_name" value="{{ old('app_name', \App\Models\Setting::get('app_name', 'Master Template SI')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Studi Kasus <span class="text-red-500">*</span></label>
                <input type="text" name="study_case" value="{{ old('study_case', \App\Models\Setting::get('study_case', 'Pemilihan Penerima Beasiswa')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Contoh: Pemilihan Karyawan Terbaik, Supplier, Lokasi Cabang Baru</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Instansi / Kampus <span class="text-red-500">*</span></label>
                <input type="text" name="instansi_name" value="{{ old('instansi_name', \App\Models\Setting::get('instansi_name', 'UNIVERSITAS NUSANTARA')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Instansi</label>
                <input type="text" name="instansi_address" value="{{ old('instansi_address', \App\Models\Setting::get('instansi_address', 'Jl. Pendidikan No. 1, Jakarta')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            {{-- BARU: Dropdown Pemilih Metode SPK --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Perhitungan Aktif <span class="text-red-500">*</span></label>
                <select name="spk_method" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    @php $currentMethod = old('spk_method', \App\Models\Setting::get('spk_method', 'saw')); @endphp
                    <option value="saw"    {{ $currentMethod === 'saw'    ? 'selected' : '' }}>SAW (Simple Additive Weighting)</option>
                    <option value="wp"     {{ $currentMethod === 'wp'     ? 'selected' : '' }}>WP (Weighted Product)</option>
                    <option value="topsis" {{ $currentMethod === 'topsis' ? 'selected' : '' }} disabled>TOPSIS (Segera Hadir)</option>
                    <option value="moora"  {{ $currentMethod === 'moora'  ? 'selected' : '' }} disabled>MOORA (Segera Hadir)</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih metode yang sesuai dengan judul skripsi Anda. Metode akan dipakai di halaman Perhitungan SAW dan Laporan PDF.</p>
            </div>

            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 font-bold transition">Simpan Branding</button>
        </form>
    </div>
</div>
@endsection