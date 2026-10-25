<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan.tema');
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme_mode'      => 'required|in:solid,gradient',
            'theme_primary'   => 'required|regex:/^#([A-Fa-f0-9]{6})$/',
            'theme_secondary' => 'required|regex:/^#([A-Fa-f0-9]{6})$/',
            'theme_accent'    => 'required|regex:/^#([A-Fa-f0-9]{6})$/',
        ]);

        Setting::set('theme_mode', $request->theme_mode);
        Setting::set('theme_primary', $request->theme_primary);
        Setting::set('theme_secondary', $request->theme_secondary);
        Setting::set('theme_accent', $request->theme_accent);

        return redirect('/admin/pengaturan/tema')->with('success', 'Tema berhasil disimpan! Lihat perubahan pada sidebar dan tombol.');
    }
}