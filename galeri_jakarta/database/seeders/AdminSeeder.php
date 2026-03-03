<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'AdminGBJ',
            'email' => 'GBJ@gmail.com', // Silakan ganti sesuai keinginan
            'password' => Hash::make('GBJ123'), // Password Anda
            'role' => 'admin', // Pastikan kolom 'role' sudah ada di tabel users
            'subscription' => 'premium', // Memberi akses penuh secara default
        ]);
    }
}