<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;

class SpkSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kriteria (total bobot wajib = 1.00)
        $kriteria = [
            ['kode_kriteria' => 'C1', 'nama_kriteria' => 'Nilai Rapor',           'tipe' => 'benefit', 'bobot' => 0.40],
            ['kode_kriteria' => 'C2', 'nama_kriteria' => 'Penghasilan Orang Tua', 'tipe' => 'cost',    'bobot' => 0.30],
            ['kode_kriteria' => 'C3', 'nama_kriteria' => 'Jumlah Tanggungan',     'tipe' => 'benefit', 'bobot' => 0.20],
            ['kode_kriteria' => 'C4', 'nama_kriteria' => 'Keaktifan Organisasi',  'tipe' => 'benefit', 'bobot' => 0.10],
        ];
        foreach ($kriteria as $k) {
            Kriteria::create($k);
        }

        // 2. Alternatif
        $alternatif = [
            ['kode_alternatif' => 'A1', 'nama_alternatif' => 'Andi Saputra'],
            ['kode_alternatif' => 'A2', 'nama_alternatif' => 'Budi Santoso'],
            ['kode_alternatif' => 'A3', 'nama_alternatif' => 'Citra Lestari'],
            ['kode_alternatif' => 'A4', 'nama_alternatif' => 'Dewi Anggraini'],
            ['kode_alternatif' => 'A5', 'nama_alternatif' => 'Eko Prasetyo'],
        ];
        foreach ($alternatif as $a) {
            Alternatif::create($a);
        }

        // 3. Matriks keputusan X [alternatif][kriteria] = nilai
        $matriks = [
            'A1' => ['C1' => 85, 'C2' => 3.5, 'C3' => 3, 'C4' => 80],
            'A2' => ['C1' => 78, 'C2' => 5.0, 'C3' => 2, 'C4' => 70],
            'A3' => ['C1' => 90, 'C2' => 2.0, 'C3' => 4, 'C4' => 90],
            'A4' => ['C1' => 82, 'C2' => 4.5, 'C3' => 1, 'C4' => 60],
            'A5' => ['C1' => 75, 'C2' => 6.0, 'C3' => 2, 'C4' => 75],
        ];

        foreach ($matriks as $kodeAlt => $nilaiPerKriteria) {
            $alt = Alternatif::where('kode_alternatif', $kodeAlt)->first();
            foreach ($nilaiPerKriteria as $kodeKr => $nilai) {
                $kr = Kriteria::where('kode_kriteria', $kodeKr)->first();
                Penilaian::create([
                    'alternatif_id' => $alt->id,
                    'kriteria_id'   => $kr->id,
                    'nilai'         => $nilai,
                ]);
            }
        }
    }
}