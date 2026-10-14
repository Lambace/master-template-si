<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    \App\Models\User::create([
        'name' => 'Administrator',
        'email' => 'admin@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role_id' => 1, // Admin
    ]);

    \App\Models\User::create([
        'name' => 'Pimpinan Sekolah',
        'email' => 'pimpinan@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role_id' => 2, // Pimpinan
    ]);
}
}
