@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-gray-700">Daftar Pengguna Sistem</h3>
    <a href="{{ url('/admin/users/create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm">
        <i class="fas fa-user-plus mr-2"></i>Tambah Pengguna
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full border-collapse border border-gray-200 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-3 text-left">Nama</th>
                <th class="border border-gray-200 px-4 py-3 text-left">Email</th>
                <th class="border border-gray-200 px-4 py-3 text-center">Role</th>
                <th class="border border-gray-200 px-4 py-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $u)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-3 font-medium">{{ $u->name }}</td>
                    <td class="border border-gray-200 px-4 py-3">{{ $u->email }}</td>
                    <td class="border border-gray-200 px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $u->role->nama_role === 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $u->role->nama_role }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-3 text-center space-x-2">
                        <a href="{{ url('/admin/users/' . $u->id . '/edit') }}" class="text-yellow-600 hover:text-yellow-800"><i class="fas fa-edit"></i></a>
                        @if($u->id !== auth()->id())
                        <form action="{{ url('/admin/users/' . $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="border border-gray-200 px-4 py-8 text-center text-gray-500">Belum ada data pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection