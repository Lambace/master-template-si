<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::orderBy('kode_alternatif')->get();
        return view('admin.alternatif.index', compact('alternatifs'));
    }

    public function create()
    {
        return view('admin.alternatif.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alternatif' => 'required|string|unique:alternatif,kode_alternatif|max:10',
            'nama_alternatif' => 'required|string|max:255',
        ]);

        Alternatif::create($request->all());

        // Trik Anti-Gagal: Redirect URL langsung
        return redirect('/admin/alternatif')->with('success', 'Alternatif berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        return view('admin.alternatif.edit', compact('alternatif'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_alternatif' => 'required|string|unique:alternatif,kode_alternatif,' . $id . '|max:10',
            'nama_alternatif' => 'required|string|max:255',
        ]);

        $alternatif = Alternatif::findOrFail($id);
        $alternatif->update($request->all());

        return redirect('/admin/alternatif')->with('success', 'Alternatif berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $alternatif->delete();

        return redirect('/admin/alternatif')->with('success', 'Alternatif berhasil dihapus!');
    }
}