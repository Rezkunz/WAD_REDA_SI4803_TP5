<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Enrico Pucci (Sesuai Gambar TP)
        User::create([
            'name' => 'Enrico Pucci',
            'email' => 'enricorico1@yopmail.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Buat Dummy Data Buku
        Book::create([
            'title' => '1984',
            'author' => 'George Orwell',
            'published_year' => 1945, // Tahun sesuai gambar TP
            'is_available' => true,
        ]);

        Book::create([
            'title' => 'Sousou No Frieren',
            'author' => 'Kanehito Yamada',
            'published_year' => 2020,
            'is_available' => true,
        ]);
    }
}