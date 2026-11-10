<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Petugas Kampus',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('admin12345'),
        ]);

        $this->category = Category::create(['name' => 'Elektronik']);
    }

    public function test_tamu_tidak_dapat_mengakses_halaman_admin_tanpa_login(): void
    {
        $response = $this->get(route('admin.reports.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_berhasil_login_dengan_kredensial_valid(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@kampus.ac.id',
            'password' => 'admin12345',
        ]);

        $response->assertRedirect(route('admin.reports.index'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_gagal_menampilkan_pesan_error_umum(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@kampus.ac.id',
            'password' => 'password_salah',
        ]);

        $response->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        $this->assertGuest();
    }

    public function test_admin_dapat_melihat_informasi_pelapor_di_detail(): void
    {
        $report = Report::create([
            'code' => 'TK-ADMIN1',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Tablet iPad Mini',
            'category_id' => $this->category->id,
            'description' => 'iPad warna abu-abu ada case biru',
            'location' => 'Gedung Rektorat Lt 3',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Dosen Pembimbing',
            'reporter_contact' => '081122334455',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.show', $report->code));

        $response->assertStatus(200);
        $response->assertSee('Dosen Pembimbing');
        $response->assertSee('081122334455');
    }

    public function test_admin_dapat_mengubah_status_dari_hilang_ke_ditemukan(): void
    {
        $report = Report::create([
            'code' => 'TK-STAT1',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Jaket Parasut Hitam',
            'category_id' => $this->category->id,
            'description' => 'Jaket merk Uniqlo ukuran L',
            'location' => 'Masjid Kampus',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Zulfikar',
            'reporter_contact' => '081234567890',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.reports.update-status', $report->code), [
            'status' => 'ditemukan',
        ]);

        $response->assertSessionHas('sukses', 'Status disimpan.');
        $this->assertEquals('ditemukan', $report->fresh()->status);
    }

    public function test_admin_dapat_mengubah_status_ke_dikembalikan_dan_mencatat_tanggal_pengembalian(): void
    {
        $report = Report::create([
            'code' => 'TK-STAT2',
            'type' => 'found',
            'status' => 'ditemukan',
            'item_name' => 'Tumbler Minum Biru',
            'category_id' => $this->category->id,
            'description' => 'Tumbler merk LocknLock warna biru dongker',
            'location' => 'Gazebo Tengah',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Petugas Kebersihan',
            'reporter_contact' => '087777777777',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.reports.update-status', $report->code), [
            'status' => 'dikembalikan',
        ]);

        $response->assertSessionHas('sukses', 'Ditandai dikembalikan.');
        $updated = $report->fresh();
        $this->assertEquals('dikembalikan', $updated->status);
        $this->assertNotNull($updated->returned_at);
    }

    public function test_status_dikembalikan_tidak_dapat_diubah_lagi(): void
    {
        $report = Report::create([
            'code' => 'TK-STAT3',
            'type' => 'lost',
            'status' => 'dikembalikan',
            'item_name' => 'Payung Lipat Biru',
            'category_id' => $this->category->id,
            'description' => 'Payung lipat motif kotak-kotak',
            'location' => 'Lobi Depan',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Sarah',
            'reporter_contact' => '088888888888',
            'returned_at' => now(),
        ]);

        // Coba ubah kembali ke hilang
        $response = $this->actingAs($this->admin)->patch(route('admin.reports.update-status', $report->code), [
            'status' => 'hilang',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('dikembalikan', $report->fresh()->status);
    }

    public function test_status_temuan_tidak_dapat_diubah_ke_hilang(): void
    {
        $report = Report::create([
            'code' => 'TK-STAT4',
            'type' => 'found',
            'status' => 'ditemukan',
            'item_name' => 'Buku Catatan Hitam',
            'category_id' => $this->category->id,
            'description' => 'Buku catatan bergaris sampul tebal',
            'location' => 'Ruang 204',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Hendra',
            'reporter_contact' => '081231231234',
        ]);

        // Status temuan hanya boleh menjadi dikembalikan
        $response = $this->actingAs($this->admin)->patch(route('admin.reports.update-status', $report->code), [
            'status' => 'hilang',
        ]);

        $response->assertSessionHasErrors(['status']);
        $this->assertEquals('ditemukan', $report->fresh()->status);
    }

    public function test_admin_dapat_mengubah_isi_laporan(): void
    {
        $report = Report::create([
            'code' => 'TK-EDIT1',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Nama Barang Salah Typo',
            'category_id' => $this->category->id,
            'description' => 'Deskripsi sebelum diperbaiki admin',
            'location' => 'Lokasi Lama',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Doni',
            'reporter_contact' => '081234567890',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.reports.update', $report->code), [
            'item_name' => 'Nama Barang Benar Setelah Diubah',
            'category_id' => $this->category->id,
            'description' => 'Deskripsi yang sudah diperbaiki dengan lengkap',
            'location' => 'Lokasi Baru Diperbaiki',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Doni Pratama',
            'reporter_contact' => '081234567899',
        ]);

        $response->assertSessionHas('sukses', 'Perubahan disimpan.');
        $updated = $report->fresh();
        $this->assertEquals('Nama Barang Benar Setelah Diubah', $updated->item_name);
        $this->assertEquals('Lokasi Baru Diperbaiki', $updated->location);
    }

    public function test_admin_dapat_menghapus_laporan(): void
    {
        $report = Report::create([
            'code' => 'TK-DEL1',
            'type' => 'lost',
            'status' => 'hilang',
            'item_name' => 'Laporan Duplikat yang Mau Dihapus',
            'category_id' => $this->category->id,
            'description' => 'Laporan uji coba yang akan dihapus admin',
            'location' => 'Test Area',
            'event_date' => now()->format('Y-m-d'),
            'reporter_name' => 'Tester',
            'reporter_contact' => '081234567890',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.reports.destroy', $report->code));

        $response->assertRedirect(route('admin.reports.index'));
        $response->assertSessionHas('sukses', 'Laporan dihapus.');
        $this->assertDatabaseMissing('reports', ['code' => 'TK-DEL1']);
    }

    public function test_admin_dapat_logout(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
