# Panduan Deployment Cloud — TemuKembali

Dokumen ini menjelaskan arsitektur komputasi awan, konfigurasi database terkelola (Supabase PostgreSQL), hosting kontainer (Render), dan estimasi biaya operasional aplikasi **TemuKembali**.

---

## 1. Arsitektur Komputasi Awan

```text
[ Pengguna / Mahasiswa ]
         │ (HTTPS / TLS 1.3)
         ▼
[ Render Cloud Edge / CDN ]
         │ (Reverse Proxy & Health Check /up)
         ▼
[ Docker Web Service Container (Render) ]
  ├── Linux + PHP 8.3 Apache + OPcache
  ├── Monolith Laravel 11 (Blade + Bootstrap 5 kustom)
  └── Zona Waktu: Asia/Makassar (WITA)
         │ (SSL / Port 5432)
         ▼
[ Managed PostgreSQL Database (Supabase Cloud) ]
  ├── Kategori tetap (Seeder)
  ├── Akun Admin Petugas (Hashed Bcrypt)
  └── Tabel Laporan Barang (Hilang / Ditemukan / Dikembalikan)
```

---

## 2. Estimasi Biaya Cloud (Cost Analysis)

Aplikasi dirancang agar dapat berjalan dengan anggaran **Rp 0 (100% Free Tier)** untuk kebutuhan akademik/proyek kampus:

| Komponen Cloud | Penyedia | Paket Layanan | Fitur | Biaya Bulanan |
|---|---|---|---|---|
| **Web Service** | Render | Free Web Service | 512 MB RAM, 0.1 CPU, Free HTTPS TLS otomatis, CI/CD terintegrasi | \$0.00 |
| **Database Terkelola** | Supabase | Free Tier PostgreSQL | 500 MB Storage, SSL connection, Automated backup mingguan | \$0.00 |
| **Penyimpanan Kode** | GitHub | Public / Private Repo | Git Version Control, Webhook Trigger Render | \$0.00 |
| **Total Estimasi** | | | | **\$0.00 / bulan** |

---

## 3. Konfigurasi Variabel Lingkungan (Environment Variables)

Pada panel **Render Dashboard -> Environment**, masukkan variabel berikut:

| Key | Value Contoh | Keterangan |
|---|---|---|
| `APP_NAME` | `TemuKembali` | Nama aplikasi |
| `APP_ENV` | `production` | Lingkungan aplikasi |
| `APP_DEBUG` | `false` | Matikan pesan debug untuk keamanan |
| `APP_KEY` | *(Generate otomatis dari Render)* | Kunci enkripsi 32-karakter |
| `APP_URL` | `https://temukembali.onrender.com` | URL publik yang diberikan Render |
| `APP_TIMEZONE` | `Asia/Makassar` | Zona waktu kampus (WITA) |
| `APP_LOCALE` | `id` | Bahasa Indonesia |
| `DB_CONNECTION` | `pgsql` | Driver database PostgreSQL |
| `DB_HOST` | `aws-0-ap-southeast-1.pooler.supabase.com` | Host Supabase (Singapore) |
| `DB_PORT` | `5432` | Port PostgreSQL |
| `DB_DATABASE` | `postgres` | Nama database Supabase |
| `DB_USERNAME` | `postgres.your-project-id` | Username database |
| `DB_PASSWORD` | `rahasia_password_db` | Password database |
| `CAMPUS_POST_LOCATION` | `Pos Keamanan Gedung Utama / Bagian Umum` | Lokasi pos pengambilan barang |
| `ADMIN_DEFAULT_PASSWORD` | `GantiPasswordKuat123!` | Password awal akun admin petugas |

---

## 4. Langkah-langkah Deployment ke Render

### Langkah 1: Siapkan Database di Supabase
1. Masuk ke [supabase.com](https://supabase.com) dan buat proyek baru (pilih region terdekat: **Singapore**).
2. Buka menu **Project Settings -> Database**.
3. Catat Connection Parameters: `Host`, `Database name`, `Port`, `User`, dan `Password`.

### Langkah 2: Hubungkan Repositori GitHub ke Render
1. Push seluruh kode proyek ini ke repositori GitHub Anda.
2. Buka [dashboard.render.com](https://dashboard.render.com).
3. Klik tombol **New +** -> pilih **Blueprint** (jika ingin menggunakan file `render.yaml`) atau **Web Service**.
4. Hubungkan repositori GitHub `temu_kembali`.

### Langkah 3: Konfigurasi Web Service
1. **Environment:** `Docker`
2. **Dockerfile Path:** `./Dockerfile`
3. **Region:** `Singapore (Southeast Asia)`
4. Masukkan seluruh variabel lingkungan dari tabel bagian 3.

### Langkah 4: Build & Otomatisasi Startup
Kontainer Docker akan secara otomatis:
- Menginstal dependensi Composer (`composer install --no-dev --optimize-autoloader`).
- Mengatur DocumentRoot ke `/var/www/html/public`.
- Menjalankan skrip `docker/run.sh` saat kontainer menyala:
  1. Menjalankan `php artisan migrate --force --seed` untuk membuat tabel dan akun admin.
  2. Mengoptimalkan cache: `php artisan config:cache`, `route:cache`, `view:cache`.
  3. Menjalankan Apache web server pada port yang ditentukan oleh Render.

---

## 5. Pengujian & Verifikasi Pasca Deployment

1. **Akses URL Publik HTTPS:** Buka URL `https://[nama-app].onrender.com`.
2. **Uji Lapor Publik:** Buat satu laporan kehilangan dan temuan, pastikan halaman sukses memunculkan kode tiket berayun dan kode dapat disalin.
3. **Uji Privasi:** Buka laporan publik pada `/laporan/{kode}`, pastikan nama dan kontak pelapor tidak terlihat.
4. **Uji Login Admin:** Masuk ke `/admin/login` dengan email `admin@kampus.ac.id` dan password yang telah diset, kelola status laporan menjadi "ditemukan" lalu "dikembalikan".
