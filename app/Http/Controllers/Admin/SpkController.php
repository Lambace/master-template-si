<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Services\Spk\SpkServiceResolver;

class SpkController extends Controller
{
    public function hasil()
    {
        $service  = SpkServiceResolver::resolve();
        $hasil    = $service->hitung();
        $metode   = $service->namaMetode();
        $kriteria = Kriteria::orderBy('kode_kriteria')->get();

        return view('admin.spk.hasil', compact('hasil', 'kriteria', 'metode'));
    }
}