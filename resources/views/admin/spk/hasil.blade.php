@extends('layouts.app')

@section('title', 'Laporan Perhitungan ' . ($metode ?? 'SPK'))
@section('page-title', 'SPK ' . \App\Models\Setting::get('study_case', 'Pemilihan Penerima Beasiswa'))

@section('content')
@php
    $fmt = fn ($v) => rtrim(rtrim(number_format((float) $v, 4), '0'), '.');
    $studyCase    = \App\Models\Setting::get('study_case', 'Pemilihan Penerima Beasiswa');
    $instansiName = \App\Models\Setting::get('instansi_name', 'NAMA INSTANSI');
    $instansiAddr = \App\Models\Setting::get('instansi_address', 'Alamat Instansi');
    $tanggalCetak = now()->locale('id')->translatedFormat('d F Y, H:i');
@endphp

<div class="flex items-center justify-between mb-6 print:hidden">
    <p class="text-sm text-gray-500">
        <i class="fas fa-info-circle mr-1"></i>
        Metode aktif: <strong class="text-blue-600">{{ $metode ?? 'SAW' }}</strong>
    </p>
    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
        <i class="fas fa-print mr-2"></i>Cetak / Simpan PDF
    </button>
</div>

@if (empty($hasil['ranking']))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded shadow-sm">
        <p class="font-bold">Data Belum Tersedia</p>
        <p class="text-sm">Silakan tambahkan data Kriteria, Alternatif, dan Penilaian terlebih dahulu.</p>
    </div>
@else
    {{-- Kop Laporan --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 text-center border-t-4 border-blue-600 print:border-none print:shadow-none">
        <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-wide">{{ $instansiName }}</h1>
        <p class="text-sm text-gray-600 mb-4">{{ $instansiAddr }}</p>
        <hr class="border-t-2 border-gray-800 mb-4 print:border-black">
        <h2 class="text-xl font-bold text-gray-800">LAPORAN PERHITUNGAN SISTEM PENDUKUNG KEPUTUSAN</h2>
        <p class="text-lg font-semibold text-gray-700 mt-1">{{ $studyCase }}</p>
        <p class="text-gray-600 font-medium">Metode: {{ $metode ?? 'SAW' }}</p>
        <p class="text-sm text-gray-500 mt-2">Dicetak pada {{ $tanggalCetak }} WIB</p>
    </div>

    {{-- Tabel 1: Kriteria & Bobot --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 print:shadow-none print:p-2">
        <h3 class="text-lg font-bold mb-3 text-gray-800 border-b pb-2">1. Data Kriteria & Bobot</h3>
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-3 py-2 text-left w-16">Kode</th>
                    <th class="border border-gray-300 px-3 py-2 text-left">Nama Kriteria</th>
                    <th class="border border-gray-300 px-3 py-2 text-center w-24">Tipe</th>
                    <th class="border border-gray-300 px-3 py-2 text-center w-24">Bobot (W)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kriteria as $k)
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-medium">{{ $k->kode_kriteria }}</td>
                        <td class="border border-gray-300 px-3 py-2">{{ $k->nama_kriteria }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center uppercase">{{ $k->tipe }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center font-bold">{{ $fmt($k->bobot) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Loop Generik: semua langkah dari service --}}
    @foreach ($hasil['trace'] as $langkah)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 print:shadow-none print:p-2">
            <h3 class="text-lg font-bold mb-1 text-gray-800">{{ $loop->iteration + 1 }}. {{ $langkah['judul'] }}</h3>
            <p class="text-xs text-gray-500 mb-4 italic">{{ $langkah['keterangan'] }}</p>

            @if ($langkah['tipe'] === 'matriks')
                @php $kolom = array_keys($langkah['matriks'][0]['nilai'] ?? []); @endphp
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 text-left w-16">Kode</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Nama</th>
                                @foreach ($kolom as $kode)
                                    <th class="border border-gray-300 px-3 py-2 text-center w-20">{{ $kode }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($langkah['matriks'] as $baris)
                                <tr>
                                    <td class="border border-gray-300 px-3 py-2 font-medium">{{ $baris['kode'] }}</td>
                                    <td class="border border-gray-300 px-3 py-2">{{ $baris['nama'] }}</td>
                                    @foreach ($kolom as $kode)
                                        <td class="border border-gray-300 px-3 py-2 text-center">{{ $fmt($baris['nilai'][$kode] ?? 0) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @elseif ($langkah['tipe'] === 'ranking')
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead>
                        <tr class="bg-green-50 print:bg-gray-100">
                            <th class="border border-gray-300 px-3 py-2 text-center w-16">Peringkat</th>
                            <th class="border border-gray-300 px-3 py-2 text-left w-16">Kode</th>
                            <th class="border border-gray-300 px-3 py-2 text-left">Nama Alternatif</th>
                            <th class="border border-gray-300 px-3 py-2 text-center w-32">Nilai Preferensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($langkah['ranking'] as $r)
                            <tr class="{{ $r['peringkat'] === 1 ? 'bg-green-100 font-bold' : 'hover:bg-gray-50' }}">
                                <td class="border border-gray-300 px-3 py-2 text-center">@if($r['peringkat'] === 1) 🏆 @endif {{ $r['peringkat'] }}</td>
                                <td class="border border-gray-300 px-3 py-2">{{ $r['kode'] }}</td>
                                <td class="border border-gray-300 px-3 py-2">{{ $r['nama'] }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ $fmt($r['nilai']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach

    {{-- Kesimpulan --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border-2 border-green-100 print:shadow-none print:border-black print:p-2">
        <div class="p-4 bg-green-50 rounded border border-green-200 print:bg-white print:border-black">
            <p class="text-sm text-gray-700">
                <strong>Kesimpulan:</strong> Berdasarkan perhitungan metode <strong>{{ $metode ?? 'SAW' }}</strong> untuk studi kasus <em>{{ $studyCase }}</em>,
                alternatif terbaik adalah
                <strong class="text-green-700">{{ $hasil['ranking'][0]['nama'] }} ({{ $hasil['ranking'][0]['kode'] }})</strong>
                dengan nilai preferensi tertinggi sebesar <strong>{{ $fmt($hasil['ranking'][0]['nilai']) }}</strong>.
            </p>
        </div>
    </div>
@endif
@endsection