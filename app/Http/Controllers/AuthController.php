<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $kredensial = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($kredensial, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();
            $namaRole = $user->role->nama_role ?? 'Unknown';

            // Cek Role secara eksplisit untuk mencegah jebakan logika
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($namaRole === 'Pimpinan') {
                return redirect()->route('pimpinan.dashboard');
            }

            // Fallback Keamanan: Jika role tidak dikenali, paksa logout dan tolak
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda tidak memiliki hak akses ke dashboard manapun.',
            ]);
        }

        // Jika password/email salah
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}