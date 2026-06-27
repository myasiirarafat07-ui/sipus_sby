<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Anggota User
        $user1 = User::create([
            'username' => 'yasiir',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);

        // Anggota Detail
        Anggota::create([
            'user_id' => $user1->id,
            'nama_lengkap' => 'Yasiir Arafat',
            'nik' => '3578012345678901',
            'alamat' => 'Jl. Pahlawan No. 1, Surabaya',
            'no_telepon' => '081234567890',
            'email' => 'yasiir@example.com',
            'tgl_registrasi' => now(),
        ]);

        // Dummy Books
        Buku::create([
            'isbn' => '978-602-8519-93-9',
            'judul' => 'Laskar Pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'tahun_terbit' => 2005,
            'kategori' => 'Novel',
            'stok' => 5,
        ]);

        Buku::create([
            'isbn' => '978-602-03-3295-6',
            'judul' => 'Bumi Manusia',
            'pengarang' => 'Pramoedya Ananta Toer',
            'penerbit' => 'Lentera Dipantara',
            'tahun_terbit' => 2005, // original 1980
            'kategori' => 'Fiksi Sejarah',
            'stok' => 3,
        ]);

        Buku::create([
            'isbn' => '978-623-242-145-6',
            'judul' => 'Pemrograman Web dengan Laravel',
            'pengarang' => 'Budi Raharjo',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2021,
            'kategori' => 'Teknologi',
            'stok' => 10,
        ]);
    }
}
