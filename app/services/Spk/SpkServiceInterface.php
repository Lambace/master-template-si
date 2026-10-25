<?php

namespace App\Services\Spk;

/**
 * KONTRAK untuk semua metode SPK (SAW, WP, TOPSIS, MOORA, dst).
 * Setiap metode WAJIB punya method hitung() yang mengembalikan array
 * dengan kunci 'ranking' dan 'trace' agar laporan PDF bisa reuse.
 */
interface SpkServiceInterface
{
    // Jalankan perhitungan, kembalikan ['ranking' => [...], 'trace' => [...]]
    public function hitung();

    // Nama metode untuk ditampilkan, mis. 'SAW', 'WP', 'TOPSIS'
    public function namaMetode(): string;
}