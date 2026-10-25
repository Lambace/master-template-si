@extends('layouts.app')

@section('title', 'Input Penilaian')
@section('page-title', 'Matriks Penilaian (Matriks X)')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
    <p class="text-sm text-yellow-800">
        <i class="fas fa-info-circle mr-2"></i>
        Isi nilai untuk setiap alternatif terhadap setiap kriteria. Pastikan semua sel terisi sebelum menyimpan.
    </p>
</div>

<form action="{{ url('/admin/penilaian') }}" method="POST">
    @csrf
    
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 text-sm min-w-[600px]">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-200 px-4 py-3 text-left sticky left-0 bg-gray-100 z-10">Alternatif</th>
                    @foreach ($kriterias as $k)
                        <th class="border border-gray-200 px-4 py-3 text-center w-24">
                            {{ $k->kode_kriteria }}
                            <div class="text-xs text-gray-500 font-normal">({{ ucfirst($k->tipe) }})</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatifs as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-3 font-bold sticky left-0 bg-white z-10">
                            {{ $a->kode_alternatif }} - {{ $a->nama_alternatif }}
                        </td>
                        @foreach ($kriterias as $k)
                            @php
                                $key = $a->id . '_' . $k->id;
                                $existingValue = $penilaians->has($key) ? $penilaians[$key]->nilai : '';
                            @endphp
                            <td class="border border-gray-200 px-2 py-3 text-center">
                                <input type="number" step="0.01" name="nilai[{{ $a->id }}][{{ $k->id }}]" 
                                       value="{{ old('nilai.' . $a->id . '.' . $k->id, $existingValue) }}" 
                                       required
                                       class="w-full border border-gray-300 rounded px-2 py-1 text-center focus:ring-2 focus:ring-blue-500 focus:outline-none @error('nilai.' . $a->id . '.' . $k->id) border-red-500 @enderror">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 shadow-sm font-bold transition">
            <i class="fas fa-save mr-2"></i>Simpan Seluruh Matriks
        </button>
    </div>
</form>
@endsection