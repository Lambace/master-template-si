<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('name')->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect('/admin/users')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed', // Password opsional saat edit
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect('/admin/users')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Mencegah admin menghapus dirinya sendiri
        if ($id == auth()->id()) {
            return redirect('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin/users')->with('success', 'Pengguna berhasil dihapus!');
    }
}