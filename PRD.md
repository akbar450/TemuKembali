# PRD — TemuKembali

**Pelaporan Barang Hilang dan Temuan di Lingkungan Kampus**

| | |
|---|---|
| Versi | 1.0 |
| Tanggal | 10 November 2026 |
| Status | Draf untuk pengembangan |

---

## 1. Ringkasan

TemuKembali adalah website sederhana untuk melaporkan **barang hilang** dan **barang yang ditemukan** di kampus. Siapa pun dapat melihat dan mencari laporan serta membuat laporan baru **tanpa perlu mendaftar akun**. Petugas kampus (admin) mengelola laporan dan memperbarui statusnya hingga barang **dikembalikan** kepada pemiliknya.

## 2. Masalah dan Tujuan

**Masalah**
- Informasi barang hilang tersebar di grup chat dan papan pengumuman, sehingga mudah terlewat.
- Sulit mencari barang berdasarkan jenis atau lokasi.
- Tidak jelas apakah sebuah barang sudah ditemukan atau sudah dikembalikan.

**Tujuan**
1. Menyediakan satu tempat untuk semua laporan barang hilang dan temuan.
2. Mempermudah pencarian berdasarkan kategori.
3. Menampilkan status barang yang jelas dan selalu terbarui.
4. Membuat proses pelaporan secepat mungkin, tanpa registrasi.

## 3. Pengguna

| Pengguna | Deskripsi | Akses |
|---|---|---|
| **Pengunjung** | Mahasiswa, dosen, staf, atau siapa pun di kampus | Melihat, mencari, dan membuat laporan tanpa login |
| **Admin** | Petugas kampus (misalnya satpam atau bagian umum) | Login, mengelola laporan, dan memperbarui status |

Tidak ada fitur registrasi. Akun admin dibuat sekali oleh pengembang (seeder) dan tidak dapat dibuat dari website.

## 4. Ruang Lingkup

### Termasuk (v1)
- Membuat laporan barang hilang atau barang temuan.
- Daftar dan detail laporan.
- Pencarian dan filter berdasarkan kategori (serta status).
- Status: **hilang**, **ditemukan**, **dikembalikan**.
- Login admin dan pengelolaan laporan.

### Tidak termasuk (v1)
- Registrasi dan akun pengunjung.
- Upload foto barang.
- Notifikasi (email, WhatsApp, atau lainnya).
- Chat antar pengguna.
- Klaim dan verifikasi otomatis.
- Dashboard statistik, grafik, dan ekspor data.
- Aplikasi mobile native.

## 5. Fitur dan Kebutuhan

### F1. Membuat laporan (Pengunjung) — Prioritas: Wajib

*Sebagai pengunjung, saya ingin melaporkan barang hilang atau temuan tanpa membuat akun.*

Form berisi:

| Kolom | Wajib | Keterangan |
|---|---|---|
| Jenis laporan | Ya | "Saya kehilangan barang" atau "Saya menemukan barang" |
| Nama barang | Ya | 3–100 karakter |
| Kategori | Ya | Dipilih dari daftar |
| Deskripsi | Ya | Ciri-ciri barang, minimal 10 karakter |
| Lokasi | Ya | Teks bebas, misalnya "Perpustakaan lantai 2" |
| Tanggal kejadian | Ya | Tidak boleh di masa depan |
| Nama pelapor | Ya | |
| Kontak pelapor | Ya | Nomor WhatsApp atau email |

Kriteria penerimaan:
- Laporan baru langsung tampil di daftar.
- Jenis "kehilangan" otomatis berstatus **hilang**; jenis "menemukan" otomatis berstatus **ditemukan**.
- Setelah dikirim, pengunjung melihat **kode laporan** (misalnya `TK-4F7K2`) sebagai bukti.
- Input tidak valid menampilkan pesan error yang jelas di kolom terkait.

### F2. Melihat dan mencari laporan (Pengunjung) — Prioritas: Wajib

*Sebagai pengunjung, saya ingin mencari barang berdasarkan kategori agar cepat menemukan yang saya cari.*

- Daftar laporan, terbaru di atas, dengan pagination.
- Pencarian kata kunci pada nama dan deskripsi.
- Filter **kategori** dan **status**.
- Setiap kartu menampilkan nama, kategori, lokasi, tanggal, dan badge status.
- Jika tidak ada hasil, tampil pesan kosong beserta tombol reset filter.

### F3. Detail laporan (Pengunjung) — Prioritas: Wajib

- Menampilkan seluruh informasi laporan kecuali kontak dan nama pelapor.
- Menampilkan petunjuk: *"Untuk mengambil atau menanyakan barang ini, datang ke pos/petugas kampus dan sebutkan kode laporan."*
- Teks petunjuk dan lokasi pos dapat diatur oleh pengembang.

### F4. Login admin — Prioritas: Wajib

- Login dengan email dan password.
- Halaman admin tidak dapat dibuka tanpa login.
- Logout tersedia.
- Tidak ada fitur "lupa password" pada v1; password diubah oleh pengembang.

### F5. Kelola laporan (Admin) — Prioritas: Wajib

*Sebagai admin, saya ingin memperbarui status laporan agar informasi di website tetap akurat.*

- Daftar semua laporan dengan filter status dan kategori, serta pencarian kode atau nama barang.
- Melihat detail lengkap, termasuk **nama dan kontak pelapor**.
- Mengubah status sesuai aturan pada bagian 6.
- Mengubah isi laporan (misalnya memperbaiki typo).
- Menghapus laporan yang tidak pantas atau duplikat, dengan konfirmasi.

## 6. Status dan Aturan

| Jenis laporan | Status awal | Dapat diubah menjadi |
|---|---|---|
| Kehilangan | `hilang` | `ditemukan` atau `dikembalikan` |
| Temuan | `ditemukan` | `dikembalikan` |

- `dikembalikan` adalah status akhir dan tidak dapat diubah lagi.
- Hanya admin yang dapat mengubah status.
- Saat status menjadi `dikembalikan`, sistem mencatat tanggal pengembalian.

**Alur singkat**

```text
Pelapor mengisi form  →  Laporan tampil di daftar (hilang / ditemukan)
        →  Pemilik datang ke petugas dengan kode laporan
        →  Admin mencocokkan dan menyerahkan barang
        →  Admin mengubah status menjadi "dikembalikan"
```

**Privasi:** nama dan kontak pelapor hanya terlihat oleh admin, tidak pernah tampil di halaman publik.

**Pencegahan spam (tanpa registrasi):** pembatasan jumlah laporan per alamat IP per jam, kolom jebakan (honeypot) tersembunyi pada form, dan admin dapat menghapus laporan.

## 7. Halaman

| Halaman | Alamat | Akses |
|---|---|---|
| Beranda (pencarian cepat + laporan terbaru + tombol lapor) | `/` | Publik |
| Daftar laporan | `/laporan` | Publik |
| Detail laporan | `/laporan/{kode}` | Publik |
| Form lapor barang | `/lapor` | Publik |
| Login admin | `/admin/login` | Publik |
| Daftar laporan admin | `/admin/laporan` | Admin |
| Detail dan ubah laporan | `/admin/laporan/{kode}` | Admin |

## 8. Data

**reports**

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| code | Kode unik laporan |
| type | `lost` / `found` |
| status | `hilang` / `ditemukan` / `dikembalikan` |
| item_name | Nama barang |
| category_id | Relasi ke kategori |
| description | Deskripsi |
| location | Lokasi (teks) |
| event_date | Tanggal kejadian |
| reporter_name | Nama pelapor (hanya admin) |
| reporter_contact | Kontak pelapor (hanya admin) |
| returned_at | Tanggal dikembalikan (nullable) |
| created_at, updated_at | Waktu dicatat |

**categories** — `id`, `name`. Daftar tetap dari seeder: Elektronik, Dokumen & Kartu, Tas & Dompet, Kunci, Pakaian & Aksesoris, Alat Tulis & Buku, Lainnya.

**users** — hanya akun admin: `id`, `name`, `email`, `password` (di-hash).

## 9. Kebutuhan Nonfungsional

- **Responsif:** nyaman dipakai di ponsel, karena sebagian besar pengguna akan mengakses dari HP.
- **Keamanan:** password di-hash, proteksi CSRF, validasi di server, output di-escape, halaman admin dilindungi login, tidak ada credential di source code.
- **Kecepatan:** daftar memakai pagination dan halaman dimuat dalam waktu wajar pada koneksi biasa.
- **Bahasa:** seluruh antarmuka dalam Bahasa Indonesia; zona waktu WITA (Asia/Makassar).
- **Ketersediaan:** aplikasi di-hosting online dan dapat diakses lewat URL publik dengan HTTPS.

## 10. Rekomendasi Teknis (dapat disesuaikan)

Karena aplikasi ini untuk tugas Komputasi Awan, berikut stack yang disarankan agar sederhana dan mudah di-deploy:

- **Aplikasi:** Laravel + Blade + Bootstrap 5.
- **Database:** PostgreSQL terkelola di cloud (misalnya Supabase).
- **Hosting:** layanan hosting cloud gratis (misalnya Render, melalui Docker).
- **Repository:** GitHub.

Hal yang perlu dibuktikan di sisi cloud: aplikasi online dengan HTTPS, database terkelola, konfigurasi lewat environment variable, dan dokumentasi arsitektur beserta estimasi biaya.

## 11. Metrik Keberhasilan

- Pengunjung dapat membuat laporan dalam waktu kurang dari 2 menit.
- Pencarian berdasarkan kategori mengembalikan hasil yang benar.
- Semua perubahan status mengikuti aturan bagian 6.
- Nama dan kontak pelapor tidak pernah tampil di halaman publik.
- Aplikasi dapat diakses lewat URL publik dan semua alur utama (F1–F5) berjalan.

## 12. Tahapan Pengerjaan

1. **Fondasi:** setup proyek, database, kategori, dan akun admin.
2. **Laporan publik:** form lapor, daftar, pencarian/filter, dan detail.
3. **Admin:** login, daftar laporan, ubah status, ubah isi, hapus.
4. **Pengujian:** uji alur utama, validasi, dan hak akses.
5. **Deployment:** online di cloud, uji di URL publik, siapkan dokumentasi.

## 13. Asumsi dan Pertanyaan Terbuka

- Pengambilan barang dilakukan lewat petugas/pos kampus. *Apakah ada lokasi pos yang akan ditampilkan?*
- Kontak pelapor hanya diketahui admin. Jika ingin pemilik dapat menghubungi penemu langsung, kontak perlu ditampilkan, dengan risiko privasi dan penyalahgunaan.
- Laporan langsung tampil tanpa persetujuan admin. Jika spam menjadi masalah, dapat ditambahkan persetujuan admin sebelum tayang.
- Masa tampil laporan tidak dibatasi pada v1.
