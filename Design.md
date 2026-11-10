# Design.md — TemuKembali

Panduan desain antarmuka untuk aplikasi TemuKembali (lihat `PRD.md`).
Fitur, alur, dan aturan bisnis mengikuti PRD. Dokumen ini hanya mengatur tampilan.

---

## 0. Cara memakai dokumen ini (untuk AI coding assistant)

1. Baca `PRD.md` dan `Design.md` sebelum membuat tampilan apa pun.
2. Bangun **mobile dulu** (lebar 360px), baru lebarkan ke tablet dan desktop.
3. Semua warna, ukuran huruf, radius, dan jarak memakai token di bagian 3. Jangan menulis nilai warna langsung di Blade.
4. Bootstrap 5 boleh dipakai untuk grid dan utilitas, tetapi **tampilan bawaan Bootstrap harus ditimpa**: warna, radius, bayangan, dan tombol. Hasil akhir tidak boleh terlihat seperti template Bootstrap.
5. Jangan menambah library UI, ikon, animasi, atau font di luar yang tertulis di sini tanpa persetujuan.
6. Satu elemen boleh tampil menonjol: **tag barang** (bagian 5.1). Sisanya sengaja tenang dan polos. Jangan menambah dekorasi.
7. Periksa daftar "Yang sengaja dihindari" (bagian 9) sebelum menyerahkan hasil.

---

## 1. Dasar pemikiran desain

**Siapa penggunanya.** Mahasiswa yang sedang kehilangan sesuatu, sering dalam keadaan panik, hampir selalu memakai HP, sering di luar ruangan dan di bawah cahaya terang. Mereka datang dengan satu tujuan: cek apakah barangnya sudah ada, atau segera melapor.

**Pekerjaan utama halaman.** Mencari, lalu melapor. Dua hal itu harus bisa dilakukan dengan satu tangan dan tanpa membaca penjelasan.

**Dari dunia apa desain ini berasal.** Pos barang hilang: label gantung berlubang, karcis penitipan, tali, kertas kardus, spidol. Bentuk label ini dipakai sebagai satu-satunya gagasan visual, karena setiap laporan memang bertindak seperti label pada sebuah barang.

**Rasa yang dituju.** Tenang, jelas, dan dapat dipercaya. Seperti petugas yang sabar, bukan aplikasi yang berusaha tampil keren.

---

## 2. Prinsip

1. **Warna berarti status.** Warna hangat, hijau, dan abu-biru hanya dipakai untuk status barang. Warna itu tidak dipakai sebagai hiasan.
2. **Tag adalah satu-satunya momen yang menonjol.** Kartu laporan berbentuk label berlubang. Elemen lain datar dan diam.
3. **Datar dan berbingkai.** Tidak ada bayangan lembut, tidak ada gradasi. Hierarki dibuat dengan garis tipis, jarak, dan ukuran huruf.
4. **Satu tangan.** Aksi utama berada di bagian bawah layar di HP. Target sentuh minimal 48px.
5. **Teks yang nyaman.** Ukuran huruf besar, jarak baris lega, kontras cukup tetapi tidak menyilaukan. Tidak ada putih murni di atas hitam murni.
6. **Kata-kata sederhana.** Kalimat pendek dan aktif, nama aksi konsisten di seluruh alur.

---

## 3. Design tokens

### 3.1 Warna

Palet dingin dan lembut agar nyaman dipandang lama dan di bawah cahaya terang. Tidak memakai hitam atau putih murni.

| Token | Nama | Nilai | Dipakai untuk |
|---|---|---|---|
| `--kertas` | Kertas kabut | `#EDF1F2` | Latar halaman |
| `--kartu` | Kartu | `#FAFBFB` | Permukaan tag, form, dialog |
| `--tinta` | Tinta | `#1E2B33` | Teks utama dan judul |
| `--tinta-redup` | Tinta redup | `#55656F` | Teks pendukung, keterangan |
| `--garis` | Garis | `#CFD8DC` | Border, pemisah |
| `--garis-tegas` | Garis tegas | `#9FB0B8` | Border input, garis putus-putus tag |
| `--biru` | Biru laut | `#2A6F86` | Tombol utama, tautan, fokus |
| `--biru-tua` | Biru laut tua | `#215A6D` | Hover dan tekan pada tombol utama |
| `--bahaya` | Merah bata | `#B3392F` | Error dan aksi menghapus |

**Warna status (khusus status barang):**

| Token | Status | Nilai | Teks di atasnya |
|---|---|---|---|
| `--tag-hilang` | Hilang | `#F2B24C` (kuning jingga) | `--tinta` |
| `--tag-ditemukan` | Ditemukan | `#8CC7A8` (hijau sage) | `--tinta` |
| `--tag-kembali` | Dikembalikan | `#B7C4CC` (abu biru) | `--tinta` |

Aturan warna:
- Semua pasangan teks dan latar memenuhi kontras WCAG AA (minimal 4.5:1). Pasangan di atas sudah diperiksa.
- Status **selalu** ditulis dengan teks ("Hilang", "Ditemukan", "Dikembalikan"), tidak hanya warna.
- Tombol utama berwarna `--biru`, bukan warna status, supaya tombol tidak terbaca sebagai status.
- Hanya tema terang pada v1. Tambahkan `color-scheme: light` pada `:root`.

### 3.2 Tipografi

Dua keluarga huruf yang jelas berbeda:

| Peran | Huruf | Alasan |
|---|---|---|
| Judul dan nama barang | **Bricolage Grotesque** (600–700) | Grotesk dengan karakter hangat dan sedikit tidak rata, terasa seperti tulisan label |
| Teks isi, form, antarmuka | **Atkinson Hyperlegible** (400, 700) | Dirancang agar huruf mirip mudah dibedakan, sangat terbaca di layar kecil |

- **Pasang sendiri (self-host)** file font di `public/assets/fonts/` dalam format `woff2` dengan `font-display: swap`. Ini lebih cepat di jaringan seluler dan tidak bergantung pada layanan pihak ketiga.
- Fallback judul: `"Bricolage Grotesque", "Segoe UI", system-ui, sans-serif`.
- Fallback isi: `"Atkinson Hyperlegible", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif`.
- Jika file font gagal dimuat, tampilan tetap harus rapi dengan font sistem.

**Skala huruf (mobile / layar ≥ 768px):**

| Peran | Ukuran | Berat | Tinggi baris |
|---|---|---|---|
| Judul halaman (h1) | 28px / 36px | 700 | 1.15 |
| Judul bagian (h2) | 22px / 26px | 600 | 1.25 |
| Judul tag, nama barang (h3) | 18px / 20px | 600 | 1.3 |
| Isi | 16px / 17px | 400 | 1.6 |
| Keterangan | 14px | 400 | 1.5 |
| Kode laporan | 16px | 700 | 1.2 |

Aturan teks:
- Ukuran huruf input **minimal 16px** agar iOS tidak memperbesar halaman saat fokus.
- Panjang baris maksimum 65 karakter pada teks isi.
- Judul memakai huruf kapital hanya di awal kalimat (sentence case). Seluruh antarmuka tidak memakai huruf kapital semua.
- Kode laporan memakai angka tabular (`font-variant-numeric: tabular-nums`) dan sedikit jarak antar huruf agar mudah dibaca keras-keras.

### 3.3 Bentuk dan kedalaman

Radius dibuat bertingkat sesuai fungsi, bukan satu nilai untuk semuanya.

| Token | Nilai | Dipakai pada |
|---|---|---|
| `--r-tag` | `6px` | Tag laporan, chip kategori |
| `--r-kontrol` | `8px` | Tombol, input, select |
| `--r-lembar` | `14px` | Dialog dan bottom sheet (sudut atas) |

- Tidak ada `box-shadow` pada kartu dan tombol. Pembeda berupa border 1px `--garis`.
- Pengecualian: cincin fokus (3.5) dan garis atas pada bar aksi bawah.
- Tidak ada gradasi di mana pun.
- Tombol berbentuk persegi panjang bersudut, **bukan** pil.

### 3.4 Jarak

Kelipatan 4px.

| Konteks | Nilai |
|---|---|
| Padding halaman | 16px (mobile), 24px (≥ 600px) |
| Jarak antar tag | 12px |
| Jarak antar bagian | 32px |
| Padding isi tag | 14px 16px |
| Lebar isi maksimum | 960px (daftar), 640px (form dan teks) |
| Tinggi minimum kontrol | 48px |

### 3.5 Fokus dan interaksi

- Cincin fokus: `outline: 3px solid var(--biru); outline-offset: 2px;` pada semua elemen interaktif. Jangan dihapus.
- Efek tekan (`:active`) pada tombol: warna menjadi `--biru-tua` dan elemen turun 1px.
- Tidak ada efek hover yang menggeser atau mengangkat kartu. Hover hanya mengubah warna.

---

## 4. Tata letak dan responsivitas

Desain mulai dari 360px.

| Lebar | Perilaku |
|---|---|
| 0–599px | Satu kolom. Bar aksi menempel di bawah. Filter status berupa kontrol segmen. Tabel admin menjadi daftar baris |
| 600–899px | Daftar tag dua kolom. Bar aksi bawah diganti tombol di header |
| ≥ 900px | Isi di tengah dengan lebar maksimum 960px. Tabel admin tampil sebagai tabel |

Aturan:
- Tidak boleh ada gulir horizontal pada halaman. Pengecualian: baris chip kategori yang memang digulir sendiri.
- Gunakan `<meta name="viewport" content="width=device-width, initial-scale=1">`.
- Bar aksi bawah memakai `padding-bottom: env(safe-area-inset-bottom)` agar tidak tertutup bar gestur di HP.
- Beri `theme-color` `#EDF1F2` pada meta tag.
- Halaman harus tetap ringan di jaringan seluler: tanpa framework JavaScript, tanpa gambar dekoratif besar, tanpa slider.
- Perataan teks: **rata kiri** di seluruh halaman, termasuk judul. Tidak ada teks rata tengah kecuali pada pesan kosong dan halaman sukses.

---

## 5. Komponen

### 5.1 Tag laporan (komponen utama)

Setiap laporan di daftar tampil sebagai label gantung: bagian kiri (stub) berwarna sesuai status, dipisah garis putus-putus dari bagian isi, dengan dua takik setengah lingkaran di garis itu, seperti label yang dirobek dari gulungan.

```text
  ┌────────┬───────────────────────────┐
  │ Hilang ┆ Dompet kulit cokelat      │
  │        ┆ Tas & Dompet              │
  │ TK-4F7 ┆ Perpustakaan lantai 2     │
  │        ┆ 8 November 2026           │
  └────────┴───────────────────────────┘
```

Aturan:
- **Stub** (lebar 88px di mobile): latar warna status, berisi label status (teks tebal) di atas dan kode laporan di bawah.
- **Isi**: nama barang (h3, maksimum 2 baris lalu dipotong), kategori, lokasi, dan tanggal. Setiap informasi di barisnya sendiri, **tanpa** dipisah titik tengah atau garis miring.
- Seluruh tag adalah satu tautan ke halaman detail. Area sentuh minimal 48px tinggi.
- Tag berstatus **Dikembalikan** memakai teks isi `--tinta-redup`, supaya yang masih dicari lebih menonjol.
- Tidak ada ikon di dalam tag. Tidak ada foto (PRD tidak mengizinkan upload foto).
- Tag tidak memakai bayangan dan tidak bergerak saat disentuh.

Contoh CSS dasar:

```css
.tag {
  position: relative;
  display: grid;
  grid-template-columns: 88px 1fr;
  background: var(--kartu);
  border: 1px solid var(--garis);
  border-radius: var(--r-tag);
  color: var(--tinta);
  text-decoration: none;
}
.tag__stub {
  background: var(--tag-warna);
  padding: 12px 10px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-radius: calc(var(--r-tag) - 1px) 0 0 calc(var(--r-tag) - 1px);
  font-weight: 700;
}
.tag__body {
  padding: 14px 16px;
  border-left: 2px dashed var(--garis-tegas);
  min-width: 0;
}
/* takik di garis putus-putus */
.tag::before,
.tag::after {
  content: "";
  position: absolute;
  left: 88px;
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: var(--kertas);
  transform: translateX(-50%);
}
.tag::before { top: -8px; }
.tag::after  { bottom: -8px; }

.tag--hilang     { --tag-warna: var(--tag-hilang); }
.tag--ditemukan  { --tag-warna: var(--tag-ditemukan); }
.tag--dikembalikan { --tag-warna: var(--tag-kembali); }
.tag--dikembalikan .tag__body { color: var(--tinta-redup); }
```

Catatan: takik memakai warna latar halaman (`--kertas`), jadi tag harus selalu berada di atas latar halaman, bukan di dalam kartu lain.

### 5.2 Tombol

| Varian | Tampilan | Dipakai untuk |
|---|---|---|
| Utama | Latar `--biru`, teks `--kartu`, radius `--r-kontrol` | Satu aksi utama per layar: Kirim laporan, Simpan perubahan |
| Sekunder | Latar transparan, border 1.5px `--biru`, teks `--biru` | Aksi pendamping: Batal, Atur ulang |
| Bahaya | Latar `--bahaya`, teks `--kartu` | Hapus laporan |
| Nonaktif | Opasitas 0.5, `cursor: not-allowed` | Aksi belum bisa dilakukan |

- Tinggi 48px, padding horizontal 20px, huruf 16px berat 700.
- **Tidak ada panah** atau ikon tambahan pada teks tombol.
- Satu layar hanya memiliki satu tombol utama.
- Saat form dikirim, tombol berubah menjadi "Mengirim…" dan dinonaktifkan agar tidak terkirim dua kali.

### 5.3 Bar aksi bawah (mobile)

Di beranda dan daftar laporan, HP menampilkan bar menempel di bawah layar berisi dua tombol berdampingan:

```text
  ┌─────────────────────────────────┐
  │ [ Saya kehilangan ] [ Saya menemukan ] │
  └─────────────────────────────────┘
```

- Latar `--kartu`, garis atas 1px `--garis`, tanpa bayangan.
- Tombol "Saya kehilangan" memakai latar `--tag-hilang` dengan teks `--tinta`; tombol "Saya menemukan" memakai `--tag-ditemukan`. Dengan begitu warna tombol sudah menjelaskan hasilnya.
- Diberi `position: sticky; bottom: 0` dan jarak aman bawah.
- Disembunyikan di halaman form, detail admin, dan layar ≥ 600px (diganti dua tombol di header).
- Beri ruang kosong di bawah isi halaman setinggi bar agar konten tidak tertutup.

### 5.4 Pencarian dan filter

- **Kolom pencarian** besar, tinggi 52px, dengan placeholder "Cari nama barang atau ciri-cirinya". Ikon kaca pembesar kecil di kiri (satu-satunya ikon di area ini).
- **Chip kategori** di bawahnya, berderet horizontal dan bisa digulir. Bentuk persegi panjang bersudut `--r-tag`, border `--garis-tegas`. Terpilih: latar `--tinta`, teks `--kartu`. Chip pertama selalu "Semua".
- **Filter status** berupa kontrol segmen empat pilihan: Semua, Hilang, Ditemukan, Dikembalikan. Setiap pilihan memiliki penanda kotak kecil berwarna sesuai status di kiri teks.
- Filter diterapkan lewat query string di URL agar bisa dibagikan dan tetap bekerja tanpa JavaScript.
- Hasil menampilkan jumlah: "12 laporan".

### 5.5 Form

- Satu kolom, label di atas input (tidak memakai label mengambang).
- Input tinggi 48px, border 1.5px `--garis-tegas`, latar `--kartu`, radius `--r-kontrol`.
- Fokus: border `--biru` ditambah cincin fokus bagian 3.5.
- Error: border `--bahaya`, pesan di bawah input berwarna `--bahaya` dengan ikon seru kecil, dihubungkan dengan `aria-describedby`. Pesan menjelaskan apa yang salah dan cara memperbaikinya.
- Kolom wajib ditandai di label dengan kata "(wajib)", bukan bintang.
- Atribut mobile: `inputmode="tel"` untuk kontak berupa nomor, `autocomplete="name"` untuk nama, `enterkeyhint` yang sesuai, `type="date"` bawaan browser untuk tanggal.
- **Pemilih jenis laporan** berada di paling atas: dua pilihan besar berdampingan ("Saya kehilangan barang" dan "Saya menemukan barang"). Pilihan aktif diberi latar warna status yang sesuai, dan garis tebal 6px di tepi atas form ikut berubah warna. Warna di form sudah memberi tahu status yang akan dibuat.
- Kolom jebakan (honeypot) disembunyikan dengan CSS dan `aria-hidden`, bukan dengan `display:none` pada atribut yang mudah dikenali.

### 5.6 Halaman detail sebagai lembar tanda terima

- Bagian atas: satu tag besar berukuran penuh (stub di atas, isi di bawah) berisi status, kode laporan, dan nama barang.
- Di bawahnya: daftar keterangan bergaya lembar tanda terima. Setiap baris memiliki label di kiri (redup) dan nilai di kanan, dipisah garis tipis `--garis`. Isinya: Kategori, Lokasi, Tanggal kejadian, Dilaporkan pada.
- Deskripsi ditampilkan sebagai paragraf biasa di bawah daftar.
- **Kotak petunjuk pengambilan**: kotak dengan border `--garis-tegas` dan latar `--kartu`, berisi langkah singkat: "Datang ke {lokasi pos} dan sebutkan kode {kode}." Ini informasi paling penting di halaman ini dan harus terlihat tanpa menggulir di HP.
- Tidak ada tombol "bagikan" atau elemen sosial tambahan.

### 5.7 Dialog konfirmasi

- Gunakan elemen `<dialog>` bawaan browser.
- Mobile: muncul sebagai bottom sheet dari bawah dengan sudut atas `--r-lembar`. Layar ≥ 600px: dialog di tengah, lebar 440px.
- Wajib untuk: menghapus laporan, menandai dikembalikan (karena tidak bisa dibatalkan).
- Judul menyebut apa yang terjadi: "Tandai sebagai dikembalikan?" Isi menyebut akibatnya: "Status tidak bisa diubah lagi setelah ini." Tombol memakai kata kerja yang sama: "Tandai dikembalikan" dan "Batal".

### 5.8 Notifikasi di layar

- Pesan sukses atau gagal tampil di bagian atas isi (bukan toast yang menghilang sendiri), dengan garis kiri 4px berwarna sesuai jenis, latar `--kartu`, dan border `--garis`.
- Pesan tetap sampai pengguna pindah halaman atau menutupnya.

### 5.9 Header dan footer

- Header: nama "TemuKembali" ditulis dengan huruf judul, rata kiri, tinggi 56px, garis bawah 1px `--garis`. Layar ≥ 600px menampilkan dua tombol lapor di kanan. Tidak ada menu hamburger di sisi publik karena hanya ada sedikit halaman.
- Footer sederhana: satu baris keterangan kampus dan tautan "Masuk admin" berukuran kecil. Tidak ada kolom tautan.

---

## 6. Halaman

### 6.1 Beranda `/`

Pembuka adalah alat yang paling dipakai, yaitu pencarian, bukan slogan besar.

```text
 ┌──────────────────────────────┐
 │ TemuKembali                   │
 ├──────────────────────────────┤
 │ Barang hilang di kampus?      │   h1, rata kiri, 2 baris
 │ Cek dulu di sini.             │
 │                                │
 │ [ Cari nama barang atau ciri ] │
 │ [Semua][Elektronik][Dompet]... │   chip digulir
 │                                │
 │ Baru dilaporkan                │   h2
 │ ┌────┬─────────────────────┐  │
 │ │Hil.┆ Dompet kulit cokelat│  │   tag
 │ └────┴─────────────────────┘  │
 │ ┌────┬─────────────────────┐  │
 │ │Dit.┆ Kunci motor Honda   │  │
 │ └────┴─────────────────────┘  │
 │  Lihat semua laporan           │
 ├──────────────────────────────┤
 │ [Saya kehilangan][Saya menemukan]│  bar bawah
 └──────────────────────────────┘
```

- Tampilkan 6 laporan terbaru.
- Di bawah daftar, satu blok pendek "Cara kerja" dengan **tiga langkah bernomor** karena isinya memang berurutan: Laporkan, Datang ke petugas bawa kode, Barang diserahkan. Tidak ada blok statistik, testimoni, atau fitur.

### 6.2 Daftar laporan `/laporan`

- Judul "Semua laporan", kolom pencarian, chip kategori, kontrol segmen status, jumlah hasil, daftar tag, pagination.
- Pagination memakai tombol sekunder "Sebelumnya" dan "Berikutnya" dengan teks "Halaman 2 dari 5". Bukan nomor halaman panjang.
- Mobile: satu kolom. ≥ 600px: dua kolom.

### 6.3 Detail laporan `/laporan/{kode}`

Susunan dan aturan di bagian 5.6. Tombol "Kembali ke daftar" dalam bentuk tautan teks di atas.

### 6.4 Form lapor `/lapor`

Urutan kolom (satu kolom):
1. Jenis laporan (dua pilihan besar)
2. Nama barang
3. Kategori
4. Ciri-ciri barang
5. Lokasi
6. Tanggal kejadian
7. Nama Anda
8. Kontak (WhatsApp atau email)

Di bawah kolom kontak ada keterangan: "Kontak hanya dilihat petugas kampus, tidak ditampilkan di website."
Tombol utama "Kirim laporan" di bawah, selebar penuh di HP.

### 6.5 Halaman sukses

- Satu tag besar di tengah berisi **kode laporan** dengan huruf besar dan jelas.
- Teks di bawahnya: "Laporan terkirim. Simpan kode ini, petugas akan memintanya."
- Tombol "Salin kode" (utama) dan tautan "Lihat laporan".
- **Satu-satunya animasi otomatis pada seluruh aplikasi:** tag turun dari atas dan berayun kecil satu kali (sekitar ±3°, 600ms) lalu diam, seperti label yang digantung. Matikan sepenuhnya saat `prefers-reduced-motion: reduce`.
- Setelah menekan "Salin kode", teks tombol berubah menjadi "Kode tersalin" selama 2 detik.

### 6.6 Login admin `/admin/login`

- Form sederhana di tengah, lebar maksimum 400px, tanpa ilustrasi.
- Kolom email dan password, tombol utama "Masuk".
- Pesan error umum: "Email atau password salah." (tidak menyebut yang mana).

### 6.7 Admin: daftar laporan `/admin/laporan`

- Mobile: tiap laporan berupa baris ringkas (kode, nama barang, badge status kecil, tanggal) yang dapat disentuh. Pencarian dan filter di atas.
- ≥ 900px: tabel dengan kolom Kode, Barang, Kategori, Status, Tanggal, dan tautan "Buka". Header tabel berlatar `--kertas`, teks tebal biasa (bukan huruf kapital), baris dipisah garis tipis.
- Badge status di admin berupa kotak kecil berwarna status dengan teks, dan **bukan** bentuk tag, agar ruang tetap padat.
- Tabel dibungkus `overflow-x: auto` sebagai pengaman.

### 6.8 Admin: detail dan ubah `/admin/laporan/{kode}`

- Atas: kode, status, dan nama barang.
- Bagian "Pelapor": nama dan kontak. Kontak berupa tautan `tel:`, `wa.me`, atau `mailto:` sesuai isi supaya satu sentuhan langsung menghubungi.
- Bagian "Ubah status": satu `select` yang hanya menampilkan transisi yang diizinkan PRD, dan tombol "Simpan status".
- Bagian "Ubah isi laporan": form yang sama dengan form publik.
- Zona bawah dipisah garis: tombol bahaya "Hapus laporan", dengan dialog konfirmasi.

---

## 7. Kondisi layar

| Kondisi | Tampilan |
|---|---|
| Memuat | Teks "Memuat laporan…" dan tiga kerangka tag berwarna `--garis` tanpa animasi berkilau |
| Kosong (tanpa hasil) | Teks polos rata tengah: "Belum ada laporan yang cocok." Di bawahnya: "Coba kata lain atau pilih Semua." dan tombol sekunder "Atur ulang filter" |
| Kosong (belum ada laporan sama sekali) | "Belum ada laporan." dan dua tombol lapor |
| Error validasi | Pesan di bawah kolom terkait, ringkasan singkat di atas form |
| Error server | Pesan: "Laporan belum terkirim. Periksa koneksi, lalu coba lagi." dengan tombol "Coba lagi". Data yang sudah diisi **tidak boleh hilang** |
| Server lambat bangun (hosting gratis) | Jika halaman pertama lama dimuat, tampilkan teks "Menyiapkan halaman, mohon tunggu sebentar." |
| 403 / 404 / 419 / 500 | Satu halaman teks pendek dengan penjelasan dan tautan kembali ke beranda. Tanpa ilustrasi |

---

## 8. Gaya bahasa

- Bahasa Indonesia sehari-hari yang sopan dan jelas. Kalimat pendek, kata kerja aktif, huruf kapital hanya di awal kalimat.
- Nama aksi **konsisten** dari tombol sampai pesan hasil:
  - "Kirim laporan" menghasilkan "Laporan terkirim."
  - "Simpan status" menghasilkan "Status disimpan."
  - "Hapus laporan" menghasilkan "Laporan dihapus."
  - "Tandai dikembalikan" menghasilkan "Ditandai dikembalikan."
- Istilah baku: *laporan*, *barang*, *kode laporan*, *petugas*, *Hilang*, *Ditemukan*, *Dikembalikan*. Jangan berganti-ganti dengan sinonim.
- Pesan error tidak meminta maaf dan tidak samar. Contoh: "Tanggal tidak boleh setelah hari ini. Pilih hari ini atau sebelumnya."
- Tidak ada seruan berlebihan, tidak ada kata sambutan basa-basi ("Selamat datang di…"), tidak ada kalimat promosi.
- Format tanggal: `8 November 2026`. Waktu memakai zona WITA.

---

## 9. Yang sengaja dihindari

Ciri-ciri berikut sering muncul di tampilan buatan otomatis. Jangan dipakai di aplikasi ini.

| Dihindari | Gantinya |
|---|---|
| Latar krem hangat dengan huruf serif dan aksen oranye tanah | Latar abu kabut dingin dengan dua keluarga grotesk |
| Latar gelap dengan satu aksen hijau neon | Hanya tema terang yang lembut |
| Seluruh konten dipotong menjadi kartu bulat identik dengan bayangan abu | Hanya satu jenis kartu bertema (tag), sisanya datar tanpa bayangan |
| Satu radius sama untuk semua elemen | Radius bertingkat sesuai fungsi |
| Gradasi sebagai hiasan | Warna datar, warna hanya untuk status |
| Tombol berbentuk pil dengan panah di ujung | Tombol persegi panjang bersudut, teks saja |
| Label kecil huruf kapital di atas setiap judul | Judul langsung, tanpa label pendahulu |
| Satu kata dalam judul diberi warna atau miring | Judul satu gaya |
| Penanda bernomor 01, 02, 03 di mana-mana | Nomor hanya pada "Cara kerja" yang memang berurutan |
| Teks meta dengan titik tengah ("A · B · C") | Setiap informasi di baris sendiri |
| Font monospace untuk label kecil | Atkinson Hyperlegible |
| Blok statistik besar, testimoni, dan logo "dipercaya oleh" | Pencarian dan laporan terbaru langsung di pembuka |
| Animasi masuk di setiap bagian dan efek hover di semua kartu | Satu animasi di halaman sukses, selebihnya diam |
| Ikon dalam lingkaran berwarna di setiap fitur | Hampir tanpa ikon |
| Hitam pekat `#000` dan putih murni `#FFF` | Tinta `#1E2B33` dan kartu `#FAFBFB` |

---

## 10. Aksesibilitas

- Atribut `lang="id"` pada `<html>`.
- Kontras teks minimal 4.5:1, kontras komponen (border input, fokus) minimal 3:1. Border input `--garis-tegas` pada `--kartu` sudah memenuhi syarat sebagai batas yang terlihat.
- Fokus keyboard selalu terlihat. Urutan tab mengikuti urutan visual.
- Gunakan elemen semantik: `header`, `nav`, `main`, `footer`, `button`, `a`, `table` dengan `th scope`.
- Setiap `input` memiliki `label` terhubung. Pesan error terhubung dengan `aria-describedby` dan diumumkan dengan `role="alert"`.
- Status tidak pernah hanya berupa warna.
- Tag adalah satu tautan dengan nama yang menyebut status, contoh: "Hilang, Dompet kulit cokelat, Perpustakaan lantai 2".
- Target sentuh minimal 48×48px, jarak antar target minimal 8px.
- Hormati `prefers-reduced-motion`: matikan animasi halaman sukses dan transisi dialog.
- Fungsi inti (cari, filter, kirim laporan, login) harus tetap bekerja tanpa JavaScript. JavaScript hanya untuk tombol salin kode, dialog konfirmasi, dan penonaktifan tombol saat mengirim.

---

## 11. Implementasi

### 11.1 Struktur file

```text
public/assets/
├── css/
│   ├── tokens.css        # variabel di bagian 3
│   ├── base.css          # reset ringan, tipografi, form, tombol
│   └── components.css    # tag, chip, segmen, dialog, bar bawah
├── fonts/                # woff2 Bricolage Grotesque dan Atkinson Hyperlegible
└── js/
    └── app.js            # salin kode, dialog, cegah kirim ganda

resources/views/
├── layouts/
│   ├── publik.blade.php
│   └── admin.blade.php
└── components/
    ├── tag-laporan.blade.php
    ├── badge-status.blade.php
    ├── chip-kategori.blade.php
    ├── segmen-status.blade.php
    ├── bar-aksi-bawah.blade.php
    ├── field.blade.php
    └── dialog-konfirmasi.blade.php
```

### 11.2 Token (potongan `tokens.css`)

```css
:root {
  color-scheme: light;

  --kertas: #EDF1F2;
  --kartu: #FAFBFB;
  --tinta: #1E2B33;
  --tinta-redup: #55656F;
  --garis: #CFD8DC;
  --garis-tegas: #9FB0B8;
  --biru: #2A6F86;
  --biru-tua: #215A6D;
  --bahaya: #B3392F;

  --tag-hilang: #F2B24C;
  --tag-ditemukan: #8CC7A8;
  --tag-kembali: #B7C4CC;

  --r-tag: 6px;
  --r-kontrol: 8px;
  --r-lembar: 14px;

  --font-judul: "Bricolage Grotesque", "Segoe UI", system-ui, sans-serif;
  --font-isi: "Atkinson Hyperlegible", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
}

html { background: var(--kertas); color: var(--tinta); font-family: var(--font-isi); }
body { font-size: 1rem; line-height: 1.6; margin: 0; }
h1, h2, h3 { font-family: var(--font-judul); color: var(--tinta); }
:focus-visible { outline: 3px solid var(--biru); outline-offset: 2px; }

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; }
}
```

### 11.3 Bootstrap

Jika Bootstrap dipakai, batasi pada grid, utilitas spasi, dan utilitas tampilan. Timpa:
- `--bs-body-bg`, `--bs-body-color`, `--bs-link-color` sesuai token.
- `.btn` (radius, tinggi, warna), `.form-control`, `.form-select`, `.alert`, `.table`.
- Matikan semua `box-shadow` bawaan.
- Jangan memakai `.card`, `.badge`, `.navbar`, atau `.rounded-pill` bawaan; gunakan komponen di bagian 5.

---

## 12. Daftar periksa sebelum selesai

- [ ] Semua halaman diperiksa pada lebar 360px, 600px, dan 1200px tanpa gulir horizontal.
- [ ] Aksi utama (cari dan lapor) bisa dijangkau satu tangan di HP.
- [ ] Hanya tag yang menonjol; tidak ada bayangan, gradasi, atau pil.
- [ ] Warna hanya dipakai untuk status dan aksi utama.
- [ ] Setiap status memiliki label teks.
- [ ] Ukuran huruf input minimal 16px.
- [ ] Kontras teks dan komponen lolos AA.
- [ ] Fokus keyboard terlihat di semua elemen interaktif.
- [ ] Halaman sukses berisi kode laporan, tombol salin, dan satu animasi yang mati saat reduced motion.
- [ ] Ada tampilan memuat, kosong, dan error untuk daftar, form, dan admin.
- [ ] Data form tidak hilang saat terjadi error.
- [ ] Cari, filter, kirim laporan, dan login tetap berfungsi tanpa JavaScript.
- [ ] Teks antarmuka mengikuti bagian 8 dan tidak memakai ciri di bagian 9.
- [ ] Font di-host sendiri dan halaman tetap rapi dengan font sistem.
