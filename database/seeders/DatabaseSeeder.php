<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Kategori tetap sesuai PRD
        $categories = [
            'Elektronik',
            'Dokumen & Kartu',
            'Tas & Dompet',
            'Kunci',
            'Pakaian & Aksesoris',
            'Alat Tulis & Buku',
            'Lainnya',
        ];

        foreach ($categories as $catName) {
            Category::firstOrCreate(['name' => $catName]);
        }

        // 2. Akun Admin tunggal sesuai PRD
        User::firstOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            [
                'name' => 'Petugas Kampus',
                'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'admin12345')),
            ]
        );

        // 3. Data sampel laporan awal untuk pengujian
        $catTas = Category::where('name', 'Tas & Dompet')->first();
        $catKunci = Category::where('name', 'Kunci')->first();
        $catElektronik = Category::where('name', 'Elektronik')->first();

        if (Report::count() === 0) {
            Report::create([
                'code' => 'TK-4F7K2',
                'type' => 'lost',
                'status' => 'hilang',
                'item_name' => 'Dompet kulit cokelat',
                'category_id' => $catTas->id,
                'description' => 'Dompet lipat kulit warna cokelat tua merk Braun Buffel, berisi KTM dan kartu perpus.',
                'location' => 'Perpustakaan lantai 2',
                'event_date' => now()->subDays(2)->format('Y-m-d'),
                'reporter_name' => 'Budi Santoso',
                'reporter_role' => 'mahasiswa',
                'reporter_id_number' => '220101045',
                'reporter_contact' => '081234567890',
            ]);

            Report::create([
                'code' => 'TK-9X2M1',
                'type' => 'found',
                'status' => 'ditemukan',
                'item_name' => 'Kunci motor Honda dengan gantungan tali merah',
                'category_id' => $catKunci->id,
                'description' => 'Satu anak kunci motor berserta gantungan tali merah bertuliskan One Heart.',
                'location' => 'Parkiran Gedung Kuliah Bersama',
                'event_date' => now()->subDay()->format('Y-m-d'),
                'reporter_name' => 'Siti Rahma',
                'reporter_role' => 'mahasiswa',
                'reporter_id_number' => '230101112',
                'reporter_contact' => 'siti.rahma@mahasiswa.ac.id',
            ]);

            Report::create([
                'code' => 'TK-1A8B9',
                'type' => 'lost',
                'status' => 'dikembalikan',
                'item_name' => 'Kalkulator Ilmiah Casio fx-991EX',
                'category_id' => $catElektronik->id,
                'description' => 'Kalkulator warna hitam putih, ada stiker inisial R di bagian belakang casing.',
                'location' => 'Laboratorium Komputer 3',
                'event_date' => now()->subDays(5)->format('Y-m-d'),
                'reporter_name' => 'Rian Hidayat',
                'reporter_role' => 'staf',
                'reporter_id_number' => '199008152020121001',
                'reporter_contact' => '089876543210',
                'returned_at' => now()->subDays(1),
            ]);
        }
    }
}
