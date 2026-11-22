<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicReportTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create(['name' => 'Elektronik']);
    }

    public function test_pengunjung_dapat_mengakses_beranda(): void
    {
        Report::create([
            'code' => 'TK-TEST1',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Laptop Lenovo ThinkPad',
            'category_id' => $this->category->id,
            'description' => 'Laptop warna hitam ada stiker github di belakang casing',
            'location' => 'Perpustakaan lantai 2',
            'event_date' => now()->subDay()->format('Y-m-d'),
            'reporter_name' => 'Rahasia Pelapor',
            'reporter_contact' => '081234567890',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Barang hilang di kampus?');
        $response->assertSee('Laptop Lenovo ThinkPad');
        $response->assertSee('Cara kerja');
        // Privasi: nama dan kontak pelapor TIDAK boleh muncul di beranda
        $response->assertDontSee('Rahasia Pelapor');
        $response->assertDontSee('081234567890');
    }

    public function test_pengunjung_dapat_memfilter_kategori_di_beranda(): void
    {
        $catKunci = Category::create(['name' => 'Kunci']);

        Report::create([
            'code' => 'TK-LAPTOP',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Laptop Lenovo ThinkPad',
            'category_id' => $this->category->id,
            'description' => 'Laptop warna hitam ada stiker github',
            'location' => 'Perpustakaan lantai 2',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Rahasia Pelapor',
            'reporter_contact' => '081234567890',
        ]);

        Report::create([
            'code' => 'TK-KUNCI',
            'type' => 'found',
            'status' => 'ditemukan',
            'item_name' => 'Kunci Motor Honda',
            'category_id' => $catKunci->id,
            'description' => 'Kunci motor warna hitam gantungan merah',
            'location' => 'Parkiran Depan',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Penemu Kunci',
            'reporter_contact' => '081234567899',
        ]);

        // Akses beranda dengan filter kategori Elektronik
        $responseElektronik = $this->get(route('home', ['kategori' => $this->category->id]));
        $responseElektronik->assertStatus(200);
        $responseElektronik->assertSee('Laptop Lenovo ThinkPad');
        $responseElektronik->assertDontSee('Kunci Motor Honda');

        // Akses beranda dengan filter kategori Kunci
        $responseKunci = $this->get(route('home', ['kategori' => $catKunci->id]));
        $responseKunci->assertStatus(200);
        $responseKunci->assertSee('Kunci Motor Honda');
        $responseKunci->assertDontSee('Laptop Lenovo ThinkPad');

        // Akses beranda dengan kategori yang belum memiliki laporan
        $catKosong = Category::create(['name' => 'Alat Tulis & Buku']);
        $responseKosong = $this->get(route('home', ['kategori' => $catKosong->id]));
        $responseKosong->assertStatus(200);
        $responseKosong->assertSee('Belum ada laporan untuk kategori ini.');
        $responseKosong->assertSee('Tampilkan semua');
    }

    public function test_pengunjung_dapat_mencari_dan_memfilter_laporan(): void
    {
        $catKunci = Category::create(['name' => 'Kunci']);

        Report::create([
            'code' => 'TK-HILANG',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Dompet Kulit Cokelat',
            'category_id' => $this->category->id,
            'description' => 'Dompet lipat warna cokelat tua',
            'location' => 'Kantin Pusat',
            'event_date' => now()->subDays(2)->format('Y-m-d'),
            'reporter_name' => 'Budi',
            'reporter_contact' => 'budi@test.com',
        ]);

        Report::create([
            'code' => 'TK-DITEMU',
            'type' => 'found',
            'status' => 'ditemukan',
            'item_name' => 'Kunci Motor Honda',
            'category_id' => $catKunci->id,
            'description' => 'Kunci motor dengan gantungan merah',
            'location' => 'Parkiran Barat',
            'event_date' => now()->subDay()->format('Y-m-d'),
            'reporter_name' => 'Ani',
            'reporter_contact' => 'ani@test.com',
        ]);

        // Cari berdasarkan kata kunci
        $searchResponse = $this->get(route('report.index', ['q' => 'Dompet']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Dompet Kulit Cokelat');
        $searchResponse->assertDontSee('Kunci Motor Honda');

        // Filter berdasarkan kategori
        $catResponse = $this->get(route('report.index', ['kategori' => $catKunci->id]));
        $catResponse->assertStatus(200);
        $catResponse->assertSee('Kunci Motor Honda');
        $catResponse->assertDontSee('Dompet Kulit Cokelat');

        // Filter berdasarkan status
        $statusResponse = $this->get(route('report.index', ['status' => 'ditemukan']));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('Kunci Motor Honda');
        $statusResponse->assertDontSee('Dompet Kulit Cokelat');
    }

    public function test_detail_laporan_menjaga_privasi_pelapor(): void
    {
        $report = Report::create([
            'code' => 'TK-DETAIL',
            'claim_pin' => '9876',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Flashdisk Sandisk 64GB',
            'category_id' => $this->category->id,
            'description' => 'Flashdisk merah ada gantungan tali hitam',
            'secret_note' => 'Ada file skripsi_final_banget.docx di root folder',
            'location' => 'Lab Komputer 1',
            'event_date' => now()->subDays(1)->format('Y-m-d'),
            'reporter_name' => 'Nama Sangat Rahasia',
            'reporter_role' => 'mahasiswa',
            'reporter_id_number' => '2201010099',
            'reporter_contact' => '089999999999',
        ]);

        $response = $this->get(route('report.show', $report->code));

        $response->assertStatus(200);
        $response->assertSee('Flashdisk Sandisk 64GB');
        $response->assertSee('Lab Komputer 1');
        $response->assertSee('Petunjuk pengambilan barang');
        $response->assertSee('TK-DETAIL');

        // Uji Privasi: nama, peran, NIM/NIK, kontak, PIN, dan ciri rahasia TIDAK boleh ada di halaman detail publik
        $response->assertDontSee('Nama Sangat Rahasia');
        $response->assertDontSee('2201010099');
        $response->assertDontSee('089999999999');
        $response->assertDontSee('9876');
        $response->assertDontSee('skripsi_final_banget.docx');
    }

    public function test_pengunjung_dapat_membuat_laporan_kehilangan(): void
    {
        $payload = [
            'type' => 'lost',
            'item_name' => 'Headphone Sony Hitam',
            'category_id' => $this->category->id,
            'description' => 'Headphone bluetooth over-ear warna hitam matte',
            'location' => 'Ruang Baca Perpustakaan',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Ahmad Fauzi',
            'reporter_role' => 'mahasiswa',
            'reporter_id_number' => '2201010055',
            'reporter_contact' => '081234567891',
        ];

        $response = $this->post(route('report.store'), $payload);

        $report = Report::where('item_name', 'Headphone Sony Hitam')->first();
        $this->assertNotNull($report);
        $this->assertEquals('hilang', $report->status);
        $this->assertEquals('mahasiswa', $report->reporter_role);
        $this->assertEquals('2201010055', $report->reporter_id_number);
        $this->assertStringStartsWith('TK-', $report->code);

        $response->assertRedirect(route('report.success', $report->code));
    }

    public function test_pengunjung_dapat_membuat_laporan_temuan(): void
    {
        $payload = [
            'type' => 'found',
            'item_name' => 'Kartu Tanda Mahasiswa (KTM)',
            'category_id' => $this->category->id,
            'description' => 'KTM atas nama mahasiswa jurusan Informatika angkatan 2024',
            'location' => 'Meja kantin gedung C',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Petugas Kantin',
            'reporter_role' => 'staf',
            'reporter_id_number' => '198501012010121002',
            'reporter_contact' => 'kantin@campus.ac.id',
        ];

        $response = $this->post(route('report.store'), $payload);

        $report = Report::where('item_name', 'Kartu Tanda Mahasiswa (KTM)')->first();
        $this->assertNotNull($report);
        $this->assertEquals('ditemukan', $report->status);
        $this->assertEquals('staf', $report->reporter_role);
        $this->assertEquals('198501012010121002', $report->reporter_id_number);

        $response->assertRedirect(route('report.success', $report->code));
    }

    public function test_honeypot_menolak_spam(): void
    {
        $payload = [
            'telepon_kantor' => 'spam bot bot bot', // bot mengisi field honeypot
            'type' => 'lost',
            'item_name' => 'Barang Palsu Spam',
            'category_id' => $this->category->id,
            'description' => 'Deskripsi spam bot 1234567890',
            'location' => 'Internet',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Spambot',
            'reporter_role' => 'tamu',
            'reporter_id_number' => '123456789',
            'reporter_contact' => 'spam@bot.com',
        ];

        $response = $this->post(route('report.store'), $payload);

        // Harus dicegah masuk database
        $this->assertDatabaseMissing('reports', ['item_name' => 'Barang Palsu Spam']);
    }

    public function test_validasi_laporan_tidak_lolos_jika_data_tidak_valid(): void
    {
        $payload = [
            'type' => 'invalid_type',
            'item_name' => 'ab', // < 3 karakter
            'category_id' => 9999, // tidak terdaftar
            'description' => 'pendek', // < 10 karakter
            'location' => '',
            'event_date' => now()->addDays(2)->format('Y-m-d'), // masa depan (tidak boleh)
            'reporter_name' => '',
            'reporter_role' => 'alien', // bukan mahasiswa/dosen/staf/tamu
            'reporter_id_number' => '',
            'reporter_contact' => '',
        ];

        $response = $this->post(route('report.store'), $payload);

        $response->assertSessionHasErrors([
            'type',
            'item_name',
            'category_id',
            'description',
            'location',
            'event_date',
            'reporter_name',
            'reporter_role',
            'reporter_id_number',
            'reporter_contact',
        ]);
    }

    public function test_pelapor_dapat_melihat_bukti_dan_tombol_cetak_pdf(): void
    {
        $report = Report::create([
            'code' => 'TK-TESTPDF',
            'claim_pin' => '9876',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Kunci Motor Vario',
            'category_id' => $this->category->id,
            'description' => 'Gantungan kunci warna merah',
            'secret_note' => 'Ada goresan kecil di gagang',
            'location' => 'Parkiran Belakang',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Budi Santoso',
            'reporter_role' => 'mahasiswa',
            'reporter_id_number' => '2201010099',
            'reporter_contact' => '081299998888',
        ]);

        $response = $this->get(route('report.success', $report->code));

        $response->assertStatus(200);
        $response->assertSee('TK-TESTPDF');
        $response->assertSee('9876');
        $response->assertSee('Unduh / Cetak Bukti (PDF)');
        $response->assertSee('BUKTI RESMI PELAPORAN');
        $response->assertSee('Budi Santoso');
        $response->assertSee('2201010099');
        $response->assertSee('Ada goresan kecil di gagang');
    }
}

