# TemuKembali 🏷️
### Sistem Pelaporan Barang Hilang dan Temuan di Lingkungan Kampus

**Proyek UTS & UAS Komputasi Awan (Cloud Computing)**  
*Monolith Web Application berbasis Laravel 11, Bootstrap 5, PostgreSQL Supabase, dan Container Deployment Render.*

---

## 1. Tentang Proyek

**TemuKembali** adalah platform berbasis web untuk memusatkan informasi barang hilang dan barang temuan di lingkungan kampus. Siapa pun (mahasiswa, dosen, staf) dapat melihat, mencari, dan membuat laporan barang hilang/temuan **tanpa perlu registrasi akun**. Petugas kampus (admin pos keamanan/bagian umum) bertindak sebagai pengelola laporan untuk memvalidasi fisik barang hingga statusnya **dikembalikan** kepada pemilik sah.

### Masalah yang Diselesaikan:
- Informasi barang tercecer di grup chat atau mading kampus yang cepat tertimbun.
- Ketidakjelasan status apakah barang masih hilang, sudah ditemukan di pos, atau sudah diambil pemiliknya.
- Perlindungan privasi: Kontak pelapor tidak disebar ke publik, hanya petugas pos kampus yang dapat menghubunginya.

---

## 2. Fitur Utama

### 👥 Publik (Pengunjung Kampus — Tanpa Login)
* **Pelaporan Cepat:** Form pelaporan satu kolom dengan pilihan "Saya kehilangan" atau "Saya menemukan", dilengkapi proteksi honeypot anti-spam dan pembatasan laju (*rate limiting*).
* **Kode Bukti Laporan:** Setiap laporan menghasilkan kode unik (contoh: `TK-4F7K2`) dengan tombol salin kode satu sentuhan.
* **Pencarian & Filter:** Cari nama barang atau ciri khas, filter chip kategori yang dapat digulir, dan kontrol segmen status berpenanda warna.
* **Tampilan Tag Tiket (Design System):** Kartu laporan bergaya label karcis pos barang hilang dengan stub warna status, garis putus-putus, dan takik robekan.
* **Privasi Terjamin:** Nama dan kontak pelapor tidak pernah ditampilkan di halaman publik; detail laporan menampilkan petunjuk pengambilan barang di pos satpam kampus.

### 🛡️ Admin (Petugas Kampus)
* **Login Khusus Petugas:** Akses panel aman di `/admin/login` tanpa registrasi publik.
* **Tabel Kelola Laporan:** Tampilan responsif (baris ringkas di mobile, tabel lengkap di layar desktop $\ge$ 900px).
* **Kontak Pelapor Langsung:** Tombol 1-klik untuk langsung menghubungi WhatsApp atau email pelapor asli.
* **Aturan Alur Status Ketat (PRD Section 6):**
  - `hilang` $\rightarrow$ dapat diubah ke `ditemukan` atau `dikembalikan`.
  - `ditemukan` $\rightarrow$ hanya dapat diubah ke `dikembalikan`.
  - `dikembalikan` $\rightarrow$ status akhir terkunci permanen, otomatis mencatat waktu pengembalian (`returned_at`).
* **Koreksi Data & Hapus:** Koreksi typo pada laporan atau hapus laporan dengan `<dialog>` konfirmasi native.

---

## 3. Arsitektur Komputasi Awan (Cloud Architecture)

```text
[ Pengguna / Mahasiswa di HP & Laptop ]
                    │
                    ▼ (HTTPS / TLS 1.3)
         [ Render Cloud Edge / CDN ]
                    │
                    ▼ (Reverse Proxy & Port Forwarding)
       [ Render Web Service (Docker Container) ]
       ├── OS: Debian Linux Slim
       ├── Runtime: PHP 8.3 Apache + OPcache
       ├── App: Laravel 11 Monolith (Blade + Bootstrap 5 kustom)
       └── Zona Waktu: Asia/Makassar (WITA)
                    │
                    ▼ (PostgreSQL SSL Connection - Port 5432)
   [ Managed Database: Supabase PostgreSQL (Singapore) ]
       ├── Tabel `categories` (7 kategori tetap)
       ├── Tabel `users` (Akun admin petugas)
       └── Tabel `reports` (Data laporan, kode unik, status, kontak aman)
```

### Estimasi Biaya Cloud (Cost Analysis):
| Layanan | Provider | Tier | Biaya / Bulan |
|---|---|---|---|
| Web Service App | Render | Free Tier | \$0.00 |
| Managed PostgreSQL | Supabase | Free Tier (500 MB) | \$0.00 |
| Git Repository | GitHub | Free | \$0.00 |
| **Total Biaya** | | | **\$0.00 (100% Gratis)** |

---

## 4. Panduan Menjalankan di Lingkungan Lokal

### Prasyarat
- PHP $\ge$ 8.2 (ekstensi `pdo_sqlite`, `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`)
- Composer $\ge$ 2.0

### Langkah Instalasi
1. Clone repositori:
   ```bash
   git clone <URL_REPOSITORI>
   cd temu_kembali
   ```

2. Instal dependensi:
   ```bash
   composer install
   ```

3. Salin konfigurasi environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan migrasi dan seeder awal:
   ```bash
   php artisan migrate --seed
   ```

5. Nyalakan local development server:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di browser pada: **`http://127.0.0.1:8000`**

### Kredensial Login Admin Lokal
- **URL Login:** `http://127.0.0.1:8000/admin/login`
- **Email:** `admin@kampus.ac.id`
- **Password:** `admin12345`

---

## 5. Pengujian Otomatis (Feature Tests)

Proyek ini dilengkapi rangkaian pengujian otomatis menggunakan PHPUnit untuk memastikan seluruh logika bisnis, validasi, privasi data, dan hak akses berfungsi dengan benar:

```bash
php artisan test
```
*atau:*
```bash
php vendor/phpunit/phpunit/phpunit
```

**Cakupan Tes (20/20 Passed - 100%):**
- Hak akses tamu ke beranda, pencarian, filter kategori, dan filter status.
- Privasi data pelapor tidak bocor di halaman publik.
- Validasi input & penolakan bot melalui spam honeypot.
- Autentikasi admin, kegagalan login dengan pesan umum, dan proteksi middleware.
- Validasi transisi status sesuai PRD dan penguncian status `dikembalikan`.
- Pengubahan isi laporan dan penghapusan laporan oleh admin.

---

## 6. Panduan Deployment Cloud (Render & Supabase)

Panduan konfigurasi langkah-demi-langkah, variabel lingkungan (`render.yaml`), dan koneksi database Supabase dapat dibaca secara lengkap pada:
👉 **[DEPLOYMENT.md](DEPLOYMENT.md)**

---

## 7. Struktur Proyek

```text
temu_kembali/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── AuthController.php      # Login & logout petugas
│   │   │   └── ReportController.php    # Kelola laporan, status & hapus
│   │   └── ReportController.php        # Beranda, daftar, lapor & detail publik
│   └── Models/
│       ├── Category.php                # Model kategori
│       ├── Report.php                  # Model laporan, kode unik & transisi status
│       └── User.php                    # Model admin
├── database/
│   ├── migrations/                     # Migrasi tabel categories & reports
│   └── seeders/DatabaseSeeder.php      # Seeder kategori & akun petugas
├── docker/
│   └── run.sh                          # Skrip inisialisasi container & migrasi cloud
├── public/
│   └── assets/
│       ├── css/                        # tokens.css, base.css, components.css
│       ├── fonts/                      # woff2 Atkinson Hyperlegible & Bricolage Grotesque
│       └── js/app.js                   # Salin kode clipboard, dialog, cegah double-submit
├── resources/views/
│   ├── admin/                          # Tampilan login & kelola laporan admin
│   ├── components/                     # Tag laporan tiket, chip, segmen, dialog
│   ├── errors/                         # Tampilan error 403, 404, 419, 500
│   ├── layouts/                        # publik.blade.php & admin.blade.php
│   └── publik/                         # home, index, create, show, success
├── Dockerfile                          # Kontainer Apache PHP 8.3 untuk Render
├── render.yaml                         # Blueprint Infrastructure-as-Code Render
├── DEPLOYMENT.md                       # Dokumentasi arsitektur cloud & estimasi biaya
├── Design.md                           # Panduan sistem desain antarmuka
└── PRD.md                              # Spesifikasi kebutuhan & aturan bisnis
```

---

## 8. Lisensi
Dibuat untuk keperluan akademik mata kuliah Komputasi Awan (Cloud Computing) 2026.
