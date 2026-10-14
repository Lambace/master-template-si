<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class PerhitunganSAW
{
    protected $kriteria;
    protected $alternatif;

    /** Matriks keputusan X: [alternatif_id][kriteria_id] = nilai mentah */
    protected array $matriksX = [];

    /**
     * Menjalankan perhitungan SAW lengkap.
     *
     * @return array{trace: array, ranking: array}
     */
    public function hitung(): array
    {
        $this->muatData();

        if ($this->kriteria->isEmpty() || $this->alternatif->isEmpty()) {
            return ['trace' => [], 'ranking' => []];
        }

        $matriksR   = $this->normalisasi();
        $matriksV   = $this->beriBobot($matriksR);
        $preferensi = $this->nilaiPreferensi($matriksV);
        $ranking    = $this->susunRanking($preferensi);

        return [
            'trace'   => $this->bangunTrace($matriksR, $matriksV, $ranking),
            'ranking' => $ranking,
        ];
    }

    protected function muatData(): void
    {
        $this->kriteria   = Kriteria::orderBy('kode_kriteria')->get();
        $this->alternatif = Alternatif::orderBy('kode_alternatif')->get();

        foreach (Penilaian::all() as $p) {
            $this->matriksX[$p->alternatif_id][$p->kriteria_id] = (float) $p->nilai;
        }
    }

    /** Langkah 2: normalisasi matriks X menjadi matriks R */
    protected function normalisasi(): array
    {
        $statistik = [];
        foreach ($this->kriteria as $k) {
            $kolom = [];
            foreach ($this->alternatif as $a) {
                $kolom[] = $this->matriksX[$a->id][$k->id] ?? 0;
            }
            $statistik[$k->id] = ['max' => max($kolom), 'min' => min($kolom)];
        }

        $matriksR = [];
        foreach ($this->alternatif as $a) {
            foreach ($this->kriteria as $k) {
                $x    = $this->matriksX[$a->id][$k->id] ?? 0;
                $stat = $statistik[$k->id];

                if ($k->tipe === 'benefit') {
                    $r = ($stat['max'] > 0) ? $x / $stat['max'] : 0;
                } else {
                    $r = ($x > 0) ? $stat['min'] / $x : 0;
                }

                $matriksR[$a->id][$k->id] = round($r, 4);
            }
        }

        return $matriksR;
    }

    /** Langkah 3: kalikan matriks R dengan bobot tiap kriteria */
    protected function beriBobot(array $matriksR): array
    {
        $matriksV = [];
        foreach ($this->alternatif as $a) {
            foreach ($this->kriteria as $k) {
                $matriksV[$a->id][$k->id] = round(($matriksR[$a->id][$k->id] ?? 0) * (float) $k->bobot, 4);
            }
        }

        return $matriksV;
    }

    /** Langkah 4: jumlahkan tiap baris matriks V */
    protected function nilaiPreferensi(array $matriksV): array
    {
        $preferensi = [];
        foreach ($this->alternatif as $a) {
            $preferensi[$a->id] = round(array_sum($matriksV[$a->id] ?? []), 4);
        }

        return $preferensi;
    }

    protected function susunRanking(array $preferensi): array
    {
        $ranking = [];
        foreach ($preferensi as $altId => $nilai) {
            $alt = $this->alternatif->firstWhere('id', $altId);
            $ranking[] = [
                'alternatif_id' => $altId,
                'kode'          => $alt->kode_alternatif,
                'nama'          => $alt->nama_alternatif,
                'nilai'         => $nilai,
            ];
        }

        usort($ranking, fn ($x, $y) => $y['nilai'] <=> $x['nilai']);

        foreach ($ranking as $i => &$item) {
            $item['peringkat'] = $i + 1;
        }

        return $ranking;
    }

    /** Menyusun trace log terstruktur untuk kebutuhan Bab IV */
    protected function bangunTrace(array $matriksR, array $matriksV, array $ranking): array
    {
        return [
            'langkah_1' => [
                'judul'      => 'Langkah 1 - Matriks Keputusan (X)',
                'keterangan' => 'Nilai mentah setiap alternatif terhadap setiap kriteria.',
                'matriks'    => $this->formatMatriks($this->matriksX),
            ],
            'langkah_2' => [
                'judul'      => 'Langkah 2 - Matriks Normalisasi (R)',
                'keterangan' => 'Benefit: Rij = Xij / max(Xj) | Cost: Rij = min(Xj) / Xij',
                'matriks'    => $this->formatMatriks($matriksR),
            ],
            'langkah_3' => [
                'judul'      => 'Langkah 3 - Matriks Terbobot (V)',
                'keterangan' => 'Vij = Wij x Rij',
                'matriks'    => $this->formatMatriks($matriksV),
            ],
            'langkah_4' => [
                'judul'      => 'Langkah 4 - Nilai Preferensi & Ranking',
                'keterangan' => 'Vi = total penjumlahan baris matriks V',
                'ranking'    => $ranking,
            ],
        ];
    }

    /** Mengubah matriks internal menjadi struktur siap render ke tabel HTML */
    protected function formatMatriks(array $matriks): array
    {
        $baris = [];
        foreach ($this->alternatif as $a) {
            $sel = [
                'kode'  => $a->kode_alternatif,
                'nama'  => $a->nama_alternatif,
                'nilai' => [],
            ];
            foreach ($this->kriteria as $k) {
                $sel['nilai'][$k->kode_kriteria] = $matriks[$a->id][$k->id] ?? 0;
            }
            $baris[] = $sel;
        }

        return $baris;
    }
}