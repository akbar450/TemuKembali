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
* **Pelaporan Cepat:** Form pelaporan satu kolom dengan pilihan "Saya kehilangan" atau "Saya menemukan", dilengkapi proteksi honeypot anti-spam (`telepon_kantor`) dan pembatasan laju (*rate limiting*).
* **Kode Bukti Laporan & PIN Verifikasi:** Setiap laporan menghasilkan kode publik unik (contoh: `TK-4F7K2`) dan **PIN Verifikasi 4 digit** rahasia yang hanya diberikan kepada pembuat laporan untuk validasi fisik saat pengambilan barang di pos satpam.
* **Ciri Rahasia / Verifikasi (`secret_note`):** Pelapor dapat menyertakan tanda pengenal tersembunyi (misal: isi dompet tersembunyi, goresan internal, wallpaper gawai) yang **tidak ditampilkan ke publik**, sehingga mencegah klaim palsu dan manipulasi oleh pihak yang tidak bertanggung jawab.
* **Pencarian & Filter Cepat:** Cari nama barang atau ciri khas, filter chip kategori yang dapat digulir langsung di beranda dan halaman direktori, serta kontrol segmen status berpenanda warna. Urutan kategori selalu konsisten dengan menempatkan opsi *"Lainnya"* di posisi paling akhir.
* **Tampilan Tag Tiket (Design System):** Kartu laporan bergaya label karcis pos barang hilang dengan stub warna status, garis putus-putus, dan takik robekan yang responsif (termasuk penyesuaian teks status panjang seperti *"Dikembalikan"* agar tetap rapi di dalam frame).
* **Privasi Terjamin:** Nama, kontak pelapor, dan ciri rahasia tidak pernah ditampilkan di halaman publik; detail laporan menampilkan petunjuk pengambilan barang di pos satpam kampus.

### 🛡️ Admin (Petugas Kampus)
* **Login Khusus Petugas:** Akses panel aman di `/admin/login` tanpa registrasi publik, dilindungi pembatasan laju (*rate limiting* 5 percobaan/menit) terhadap serangan *brute force*.
* **Tabel Kelola Laporan:** Tampilan responsif (baris ringkas di mobile, tabel lengkap di layar desktop $\ge$ 900px) dilengkapi pencarian kata kunci dan filter kategori/status yang dieksekusi melalui tombol filter.
* **Verifikasi Kepemilikan Sah:** Petugas dapat mencocokkan PIN Verifikasi dan membaca Ciri Rahasia pada panel admin untuk memastikan barang diserahkan kepada pemilik yang sah.
* **Kontak Pelapor Langsung:** Tombol 1-klik untuk langsung menghubungi WhatsApp atau email pelapor asli.
* **Aturan Alur Status Ketat (PRD Section 6):**
  - `hilang` $\rightarrow$ dapat diubah ke `ditemukan` atau `dikembalikan`.
  - `ditemukan` $\rightarrow$ hanya dapat diubah ke `dikembalikan`.
  - `dikembalikan` $\rightarrow$ status akhir terkunci permanen, otomatis mencatat waktu pengembalian (`returned_at`).
* **Koreksi Data & Hapus:** Koreksi typo pada laporan atau hapus laporan dengan `<dialog>` konfirmasi native.

### 🔒 Keamanan & Ketahanan Sistem
* **Anti-Klaim Palsu:** Sistem PIN Verifikasi dan catatan rahasia (*secret note*) yang hanya dapat diakses oleh petugas kampus.
* **HTTP Security Headers:** Middleware kustom yang menerapkan header `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`, dan `Permissions-Policy`.
* **Reverse Proxy Trust:** Mendukung terminasi SSL dan header proxy Render Cloud (`trustProxies('*')`) untuk memastikan pendeteksian IP dan HTTPS yang akurat.

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

**Cakupan Tes (21/21 Passed - 100%, 86 assertions):**
- Hak akses tamu ke beranda, pencarian, filter kategori, filter beranda, dan filter status.
- Pemfilteran chip kategori di beranda dengan pembaruan dinamis dan penanganan *empty state*.
- Privasi data pelapor (nama, kontak, dan ciri rahasia) tidak bocor di halaman publik.
- Validasi input & penolakan bot melalui spam honeypot (`telepon_kantor`).
- Autentikasi admin, proteksi laju percobaan login (*rate limiting* anti-brute force), kegagalan login dengan pesan umum, dan proteksi middleware.
- Validasi transisi status sesuai PRD dan penguncian status `dikembalikan`.
- Pengubahan isi laporan, verifikasi PIN/ciri rahasia, dan penghapusan laporan oleh admin.

---

## 6. Panduan Deployment Cloud (Render & Supabase)

Panduan konfigurasi langkah-demi-langkah, variabel lingkungan (`render.yaml`), dan koneksi database Supabase dapat dibaca secara lengkap pada:
👉 **[DEPLOYMENT.md](DEPLOYMENT.md)**

---

## 7. Struktur Proyek

```text
temu_kembali/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php      # Login & logout petugas (rate-limited)
│   │   │   │   └── ReportController.php    # Kelola laporan, status & hapus
│   │   │   └── ReportController.php        # Beranda, filter chip, lapor & detail publik
│   │   └── Middleware/
│   │       └── SecurityHeaders.php         # HTTP security headers (X-Frame, nosniff, dll)
│   └── Models/
│       ├── Category.php                    # Model kategori (scopeOrdered: 'Lainnya' terakhir)
│       ├── Report.php                      # Model laporan, kode unik, PIN verifikasi, status
│       └── User.php                        # Model admin petugas kampus
├── database/
│   ├── migrations/                         # Migrasi tabel categories, reports & verifikasi
│   └── seeders/DatabaseSeeder.php          # Seeder kategori & akun petugas
├── docker/
│   └── run.sh                              # Skrip inisialisasi container & migrasi cloud
├── public/
│   └── assets/
│       ├── css/                            # tokens.css, base.css, components.css
│       ├── fonts/                          # woff2 Atkinson Hyperlegible & Bricolage Grotesque
│       └── js/app.js                       # Salin kode clipboard, dialog, cegah double-submit
├── resources/views/
│   ├── admin/                              # Tampilan login & kelola laporan admin
│   ├── components/                         # Tag laporan tiket, chip, segmen, dialog
│   ├── errors/                             # Tampilan error 403, 404, 419, 500
│   ├── layouts/                            # publik.blade.php & admin.blade.php
│   └── publik/                             # home, index, create, show, success
├── Dockerfile                              # Kontainer Apache PHP 8.3 untuk Render
├── render.yaml                             # Blueprint Infrastructure-as-Code Render
├── DEPLOYMENT.md                           # Dokumentasi arsitektur cloud & estimasi biaya
├── Design.md                               # Panduan sistem desain antarmuka
└── PRD.md                                  # Spesifikasi kebutuhan & aturan bisnis
```

---

## 8. Lisensi
Dibuat untuk keperluan akademik mata kuliah Komputasi Awan (Cloud Computing) 2026.
