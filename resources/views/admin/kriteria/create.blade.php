@extends('layouts.app')

@section('title', 'Tambah Kriteria')
@section('page-title', 'Tambah Kriteria Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm p-6">
    <form action="{{ url('/admin/kriteria') }}" method="POST">
        @csrf
        
        <!-- Kode Kriteria -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kode Kriteria <span class="text-red-500">*</span>
            </label>
            <input type="text" name="kode_kriteria" value="{{ old('kode_kriteria') }}" required maxlength="10" placeholder="Contoh: C1"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('kode_kriteria') border-red-500 @enderror">
            @error('kode_kriteria')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama Kriteria -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Kriteria <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_kriteria" value="{{ old('nama_kriteria') }}" required placeholder="Contoh: Nilai Rapor"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('nama_kriteria') border-red-500 @enderror">
            @error('nama_kriteria')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tipe Kriteria -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tipe Kriteria <span class="text-red-500">*</span>
            </label>
            <select name="tipe" required 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('tipe') border-red-500 @enderror">
                <option value="">-- Pilih Tipe --</option>
                <option value="benefit" {{ old('tipe') == 'benefit' ? 'selected' : '' }}>Benefit (Semakin besar semakin baik)</option>
                <option value="cost" {{ old('tipe') == 'cost' ? 'selected' : '' }}>Cost (Semakin kecil semakin baik)</option>
            </select>
            @error('tipe')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bobot -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Bobot (0.00 - 1.00) <span class="text-red-500">*</span>
            </label>
            <input type="number" name="bobot" value="{{ old('bobot') }}" step="0.01" min="0" max="1" required placeholder="Contoh: 0.40"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('bobot') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-info-circle mr-1"></i> Total bobot semua kriteria harus berjumlah tepat 1.00
            </p>
            @error('bobot')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ url('/admin/kriteria') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">
                <i class="fas fa-save mr-2"></i>Simpan Kriteria
            </button>
        </div>
    </form>
</div>
@endsection