<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\Spk\SpkServiceInterface;

class PerhitunganSAW implements SpkServiceInterface
{
    protected $kriteria;
    protected $alternatif;
    protected array $matriksX = [];

    public function namaMetode(): string
    {
        return 'SAW';
    }

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
                $matriksR[$a->id][$k->id] = $k->tipe === 'benefit'
                    ? ($stat['max'] > 0 ? round($x / $stat['max'], 4) : 0)
                    : ($x > 0 ? round($stat['min'] / $x, 4) : 0);
            }
        }
        return $matriksR;
    }

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

    /** Trace generik: daftar langkah dengan tipe 'matriks' atau 'ranking' */
    protected function bangunTrace(array $matriksR, array $matriksV, array $ranking): array
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
                'judul'      => 'Matriks Normalisasi (R)',
                'keterangan' => 'Benefit: Rij = Xij / max(Xj) | Cost: Rij = min(Xj) / Xij',
                'matriks'    => $this->formatMatriks($matriksR),
            ],
            [
                'tipe'       => 'matriks',
                'judul'      => 'Matriks Terbobot (V)',
                'keterangan' => 'Vij = Wij x Rij',
                'matriks'    => $this->formatMatriks($matriksV),
            ],
            [
                'tipe'       => 'ranking',
                'judul'      => 'Nilai Preferensi & Ranking',
                'keterangan' => 'Vi = total penjumlahan baris matriks V',
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
}