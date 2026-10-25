<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    // Menampilkan halaman pengaturan tata letak
    public function index()
    {
        return view('admin.pengaturan.tata_letak');
    }

    // Menyimpan perubahan tata letak ke tabel settings
    public function update(Request $request)
    {
        $request->validate([
            // PERBAIKAN: tambahkan 'top' agar opsi Menu Horisontal diizinkan
            'layout_sidebar_position' => 'required|in:left,right,top',
            'layout_sidebar_mode'     => 'required|in:full,mini',
            'layout_content_width'    => 'required|in:full,boxed',
            'layout_corner'           => 'required|in:rounded,sharp',
            'layout_scale'            => 'required|in:small,normal,large',
        ]);

        // Simpan semua pilihan layout ke tabel settings sekaligus
        foreach ($request->only([
            'layout_sidebar_position',
            'layout_sidebar_mode',
            'layout_content_width',
            'layout_corner',
            'layout_scale',
        ]) as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect('/admin/pengaturan/tata-letak')
                     ->with('success', 'Tata letak berhasil disimpan! Lihat perubahannya di seluruh aplikasi.');
    }
}