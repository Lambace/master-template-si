<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // 1. Tampilkan halaman Profil & Branding
    public function index()
    {
        return view('admin.pengaturan.profile');
    }

    // 2. Update Profil User (Nama, Email, Password)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        // Hanya update password jika field password diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return back()->with('success_profile', 'Profil Anda berhasil diperbarui!');
    }

    // 3. Update Branding Aplikasi (Nama App, Instansi, Alamat)
    public function updateBranding(Request $request)
{
    $request->validate([
        'app_name'         => 'required|string|max:255',
        'study_case'       => 'required|string|max:255',
        'instansi_name'    => 'required|string|max:255',
        'instansi_address' => 'nullable|string|max:255',
        'spk_method'       => 'required|in:saw,wp,topsis,moora',
    ]);

    Setting::set('app_name',         $request->app_name);
    Setting::set('study_case',       $request->study_case);
    Setting::set('instansi_name',    $request->instansi_name);
    Setting::set('instansi_address', $request->instansi_address);
    Setting::set('spk_method',       $request->spk_method);

    return back()->with('success_branding', 'Branding & metode perhitungan berhasil disimpan!');
}