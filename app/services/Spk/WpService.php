<?php

namespace App\Services\Spk;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class WpService implements SpkServiceInterface
{
    protected $kriteria;
    protected $alternatif;
    protected array $matriksX = [];

    public function namaMetode(): string
    {
        return 'WP';
    }

    public function hitung(): array
    {
        $this->muatData();

        if ($this->kriteria->isEmpty() || $this->alternatif->isEmpty()) {
            return ['trace' => [], 'ranking' => []];
        }

        $bobotW   = $this->bobotTernormalisasi();
        $matriksP = $this->matriksTerpangkat($bobotW);
        $vectorS  = $this->vectorS($matriksP);
        $vectorV  = $this->vectorV($vectorS);
        $ranking  = $this->susunRanking($vectorV);

        return [
            'trace'   => $this->bangunTrace($bobotW, $matriksP, $vectorS, $vectorV, $ranking),
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

    /** Langkah 2: w_j = W_j / total(W) */
    protected function bobotTernormalisasi(): array
    {
        $total = $this->kriteria->sum('bobot') ?: 1;
        $w = [];
        foreach ($this->kriteria as $k) {
            $w[$k->id] = round((float) $k->bobot / $total, 4);
        }
        return $w;
    }

    /** Langkah 3: X^W  (benefit pangkat +w, cost pangkat -w) */
    protected function matriksTerpangkat(array $w): array
    {
        $p = [];
        foreach ($this->alternatif as $a) {
            foreach ($this->kriteria as $k) {
                $x = max($this->matriksX[$a->id][$k->id] ?? 0, 0.0001);
                $pangkat = $k->tipe === 'benefit' ? $w[$k->id] : -$w[$k->id];
                $p[$a->id][$k->id] = round(pow($x, $pangkat), 4);
            }
        }
        return $p;
    }

    /** Langkah 4: S_i = hasil kali semua X^W pada baris i */
    protected function vectorS(array $p): array
    {
        $s = [];
        foreach ($this->alternatif as $a) {
            $produk = 1;
            foreach ($this->kriteria as $k) {
                $produk *= ($p[$a->id][$k->id] ?? 1);
            }
            $s[$a->id] = round($produk, 4);
        }
        return $s;
    }

    /** Langkah 5: V_i = S_i / total(S) */
    protected function vectorV(array $s): array
    {
        $total = array_sum($s) ?: 1;
        $v = [];
        foreach ($s as $id => $val) {
            $v[$id] = round($val / $total, 4);
        }
        return $v;
    }

    protected function susunRanking(array $v): array
    {
        $ranking = [];
        foreach ($v as $altId => $nilai) {
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

    protected function bangunTrace(array $w, array $p, array $s, array $v, array $ranking): array
    {
        return [
            [
                'tipe'       => 'matriks',
                'judul'      => 'Matriks Keputusan (X)',
                'keterangan' => 'Nilai mentah setiap alternatif terhadap setiap kriteria.',
                'matriks'    => $this->formatMatriks($this->matriksX),
            ],
            [
                'tipe'       => 'matriks',
                'judul'      => 'Bobot Ternormalisasi (W)',
                'keterangan' => 'w_j = W_j / total(W)',
                'matriks'    => $this->formatBarisKriteria($w),
            ],
            [
                'tipe'       => 'matriks',
                'judul'      => 'Matriks Terpangkat (X^W)',
                'keterangan' => 'Benefit: x^(+w) | Cost: x^(-w)',
                'matriks'    => $this->formatMatriks($p),
            ],
            [
                'tipe'       => 'matriks',
                'judul'      => 'Vector S & V',
                'keterangan' => 'S_i = hasil kali baris | V_i = S_i / total(S)',
                'matriks'    => $this->formatBarisAlternatif($s, $v),
            ],
            [
                'tipe'       => 'ranking',
                'judul'      => 'Ranking Akhir',
                'keterangan' => 'Diurutkan berdasarkan V tertinggi',
                'ranking'    => $ranking,
            ],
        ];
    }

    protected function formatMatriks(array $matriks): array
    {
        $baris = [];
        foreach ($this->alternatif as $a) {
            $sel = ['kode' => $a->kode_alternatif, 'nama' => $a->nama_alternatif, 'nilai' => []];
            foreach ($this->kriteria as $k) {
                $sel['nilai'][$k->kode_kriteria] = $matriks[$a->id][$k->id] ?? 0;
            }
            $baris[] = $sel;
        }
        return $baris;
    }

    /** Baris = kriteria, kolom tunggal 'W' */
    protected function formatBarisKriteria(array $w): array
    {
        $baris = [];
        foreach ($this->kriteria as $k) {
            $baris[] = ['kode' => $k->kode_kriteria, 'nama' => $k->nama_kriteria, 'nilai' => ['W' => $w[$k->id]]];
        }
        return $baris;
    }

    /** Baris = alternatif, kolom 'S' dan 'V' */
    protected function formatBarisAlternatif(array $s, array $v): array
    {
        $baris = [];
        foreach ($this->alternatif as $a) {
            $baris[] = [
                'kode'  => $a->kode_alternatif,
                'nama'  => $a->nama_alternatif,
                'nilai' => ['S' => $s[$a->id] ?? 0, 'V' => $v[$a->id] ?? 0],
            ];
        }
        return $baris;
    }
}