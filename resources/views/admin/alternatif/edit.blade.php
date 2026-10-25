@extends('layouts.app')

@section('title', 'Edit Alternatif')
@section('page-title', 'Edit Data Alternatif')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm p-6">
    <form action="{{ url('/admin/alternatif/' . $alternatif->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Alternatif <span class="text-red-500">*</span></label>
            <input type="text" name="kode_alternatif" value="{{ old('kode_alternatif', $alternatif->kode_alternatif) }}" required maxlength="10"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('kode_alternatif') border-red-500 @enderror">
            @error('kode_alternatif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Alternatif <span class="text-red-500">*</span></label>
            <input type="text" name="nama_alternatif" value="{{ old('nama_alternatif', $alternatif->nama_alternatif) }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('nama_alternatif') border-red-500 @enderror">
            @error('nama_alternatif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ url('/admin/alternatif') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm">
                <i class="fas fa-save mr-2"></i>Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection