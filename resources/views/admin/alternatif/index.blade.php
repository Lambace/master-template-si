@extends('layouts.app')

@section('title', 'Manajemen Alternatif')
@section('page-title', 'Manajemen Alternatif')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-gray-700">Daftar Alternatif</h3>
    <a href="{{ url('/admin/alternatif/create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm">
        <i class="fas fa-plus mr-2"></i>Tambah Alternatif
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full border-collapse border border-gray-200 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-3 text-left">Kode</th>
                <th class="border border-gray-200 px-4 py-3 text-left">Nama Alternatif</th>
                <th class="border border-gray-200 px-4 py-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alternatifs as $a)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-3 font-bold">{{ $a->kode_alternatif }}</td>
                    <td class="border border-gray-200 px-4 py-3">{{ $a->nama_alternatif }}</td>
                    <td class="border border-gray-200 px-4 py-3 text-center space-x-2">
                        <a href="{{ url('/admin/alternatif/' . $a->id . '/edit') }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ url('/admin/alternatif/' . $a->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                    <td colspan="3" class="border border-gray-200 px-4 py-8 text-center text-gray-500">
                        Belum ada data alternatif. Silakan tambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection