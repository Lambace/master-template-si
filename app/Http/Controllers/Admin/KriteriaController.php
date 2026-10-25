<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    // 1. Tampilkan daftar kriteria
    public function index()
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        return view('admin.kriteria.index', compact('kriterias'));
    }

    // 2. Tampilkan form tambah kriteria
    public function create()
    {
        return view('admin.kriteria.create');
    }

    // 3. Simpan kriteria baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|string|unique:kriteria,kode_kriteria|max:10',
            'nama_kriteria' => 'required|string|max:255',
            'tipe'          => 'required|in:benefit,cost',
            'bobot'         => 'required|numeric|min:0|max:1',
        ]);

        Kriteria::create($request->all());

        // PERBAIKAN: Gunakan redirect URL langsung, hindari route()
        return redirect('/admin/kriteria')->with('success', 'Kriteria berhasil ditambahkan!');
    }

    // 4. Tampilkan form edit
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    // 5. Update kriteria
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kriteria' => 'required|string|unique:kriteria,kode_kriteria,' . $id . '|max:10',
            'nama_kriteria' => 'required|string|max:255',
            'tipe'          => 'required|in:benefit,cost',
            'bobot'         => 'required|numeric|min:0|max:1',
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->all());

        // PERBAIKAN: Gunakan redirect URL langsung
        return redirect('/admin/kriteria')->with('success', 'Kriteria berhasil diperbarui!');
    }

    // 6. Hapus kriteria
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        // PERBAIKAN: Gunakan redirect URL langsung
        return redirect('/admin/kriteria')->with('success', 'Kriteria berhasil dihapus!');
    }
}