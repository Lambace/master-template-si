@extends('layouts.app')

@section('title', 'Manajemen Kriteria')
@section('page-title', 'Manajemen Kriteria')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-gray-700">Daftar Kriteria SPK</h3>
    
    {{-- SOLUSI MUTLAK: Gunakan url() langsung, hindari route() yang bermasalah --}}
    <a href="{{ url('/admin/kriteria/create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm">
        <i class="fas fa-plus mr-2"></i>Tambah Kriteria
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full border-collapse border border-gray-200 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-3 text-left">Kode</th>
                <th class="border border-gray-200 px-4 py-3 text-left">Nama Kriteria</th>
                <th class="border border-gray-200 px-4 py-3 text-center">Tipe</th>
                <th class="border border-gray-200 px-4 py-3 text-center">Bobot</th>
                <th class="border border-gray-200 px-4 py-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kriterias as $k)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-3 font-bold">{{ $k->kode_kriteria }}</td>
                    <td class="border border-gray-200 px-4 py-3">{{ $k->nama_kriteria }}</td>
                    <td class="border border-gray-200 px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $k->tipe === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($k->tipe) }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-3 text-center font-bold">{{ $k->bobot }}</td>
                    <td class="border border-gray-200 px-4 py-3 text-center space-x-2">
                        
                        {{-- Tombol Edit menggunakan url() --}}
                        <a href="{{ url('/admin/kriteria/' . $k->id . '/edit') }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        {{-- Tombol Hapus menggunakan url() --}}
                        <form action="{{ url('/admin/kriteria/' . $k->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kriteria ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border border-gray-200 px-4 py-8 text-center text-gray-500">
                        Belum ada data kriteria. Silakan tambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection