<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // 1. Tampilkan halaman matriks penilaian
    public function index()
    {
        $alternatifs = Alternatif::orderBy('kode_alternatif')->get();
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        
        // Ambil semua data penilaian yang sudah ada untuk di-pre-fill di form
        $penilaians = Penilaian::all()->keyBy(function($item) {
            return $item->alternatif_id . '_' . $item->kriteria_id;
        });

        return view('admin.penilaian.index', compact('alternatifs', 'kriterias', 'penilaians'));
    }

    // 2. Simpan/Update seluruh matriks sekaligus
    public function store(Request $request)
    {
        // Validasi: pastikan setiap sel terisi
        $request->validate([
            'nilai.*.*' => 'required|numeric|min:0',
        ], [
            'nilai.*.*.required' => 'Semua sel penilaian wajib diisi.',
            'nilai.*.*.numeric' => 'Nilai harus berupa angka.',
        ]);

        $dataNilai = $request->input('nilai');

        // Loop melalui setiap Alternatif dan Kriteria
        foreach ($dataNilai as $altId => $kriteriaValues) {
            foreach ($kriteriaValues as $krId => $nilai) {
                // updateOrCreate: jika ada, update. Jika tidak, buat baru.
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $altId,
                        'kriteria_id'   => $krId,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }

        return redirect('/admin/penilaian')->with('success', 'Matriks penilaian berhasil disimpan! Silakan cek halaman Perhitungan SAW.');
    }
}