@extends('layouts.publik')

@section('title', 'Lapor Barang')

@section('content')
    <div class="tk-container--narrow mx-auto">
        <a href="{{ route('home') }}" class="d-inline-block mb-3 text-decoration-none" style="font-size: 14px; font-weight: 600; color: var(--biru);">
            ← Kembali ke beranda
        </a>

        <h1 class="mb-3">Lapor barang</h1>

        <div class="form-box {{ old('type', $defaultType) === 'found' ? 'form-box--found' : 'form-box--lost' }}">
            <form action="{{ route('report.store') }}" method="POST" data-prevent-double-submit novalidate>
                @csrf

                {{-- Kolom Honeypot Jebakan Spam (Section 5.5 & PRD 6) --}}
                <input type="text" name="telepon_kantor" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

                {{-- 1. Pemilih Jenis Laporan --}}
                <div class="form-group mb-4">
                    <span class="form-label">Jenis laporan <span class="wajib">(wajib)</span></span>
                    <div class="jenis-laporan-selector" role="radiogroup" aria-label="Jenis laporan">
                        <label>
                            <input type="radio" 
                                   name="type" 
                                   value="lost" 
                                   class="jenis-laporan-radio" 
                                   {{ old('type', $defaultType) === 'lost' ? 'checked' : '' }}>
                            <span class="jenis-laporan-label">
                                Saya kehilangan barang
                            </span>
                        </label>
                        <label>
                            <input type="radio" 
                                   name="type" 
                                   value="found" 
                                   class="jenis-laporan-radio" 
                                   {{ old('type', $defaultType) === 'found' ? 'checked' : '' }}>
                            <span class="jenis-laporan-label">
                                Saya menemukan barang
                            </span>
                        </label>
                    </div>
                    @error('type')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 2. Nama Barang --}}
                <div class="form-group">
                    <label for="item_name" class="form-label">
                        Nama barang <span class="wajib">(wajib)</span>
                    </label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}" 
                           class="form-control @error('item_name') is-invalid @enderror" 
                           placeholder="Contoh: Dompet kulit cokelat" 
                           required 
                           aria-describedby="@error('item_name') item_name-error @enderror">
                    @error('item_name')
                        <div id="item_name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 3. Kategori --}}
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        Kategori <span class="wajib">(wajib)</span>
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            class="form-select @error('category_id') is-invalid @enderror" 
                            required 
                            aria-describedby="@error('category_id') category_id-error @enderror">
                        <option value="">Pilih kategori barang</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div id="category_id-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 4. Ciri-ciri Barang / Deskripsi --}}
                <div class="form-group">
                    <label for="description" class="form-label">
                        Ciri-ciri barang <span class="wajib">(wajib)</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control @error('description') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Warna, merk, tipe, atau ciri umum yang aman dilihat publik" 
                              required 
                              aria-describedby="@error('description') description-error @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div id="description-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ciri Khusus Rahasia untuk Verifikasi Kepemilikan (Anti Manipulasi & Klaim Palsu) --}}
                <div class="form-group">
                    <label for="secret_note" class="form-label">
                        Ciri rahasia / verifikasi <span class="wajib">(rahasia untuk petugas)</span>
                    </label>
                    <textarea id="secret_note" 
                              name="secret_note" 
                              class="form-control @error('secret_note') is-invalid @enderror" 
                              rows="2" 
                              placeholder="Contoh: Isi uang dalam dompet, stiker di balik casing, goresan khas, atau nomor seri/IMEI" 
                              aria-describedby="secret-note-desc @error('secret_note') secret_note-error @enderror">{{ old('secret_note') }}</textarea>
                    <span id="secret-note-desc" class="form-keterangan">
                        🔒 <strong>Rahasia:</strong> Hanya dilihat oleh petugas kampus untuk menguji kecocokan saat pengambilan barang. Tidak ditampilkan di website.
                    </span>
                    @error('secret_note')
                        <div id="secret_note-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 5. Lokasi --}}
                <div class="form-group">
                    <label for="location" class="form-label">
                        Lokasi kejadian atau temuan <span class="wajib">(wajib)</span>
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}" 
                           class="form-control @error('location') is-invalid @enderror" 
                           placeholder="Contoh: Perpustakaan lantai 2 atau Parkiran motor barat" 
                           required 
                           aria-describedby="@error('location') location-error @enderror">
                    @error('location')
                        <div id="location-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 6. Tanggal Kejadian --}}
                <div class="form-group">
                    <label for="event_date" class="form-label">
                        Tanggal kejadian <span class="wajib">(wajib)</span>
                    </label>
                    <input type="date" 
                           id="event_date" 
                           name="event_date" 
                           value="{{ old('event_date', date('Y-m-d')) }}" 
                           max="{{ date('Y-m-d') }}" 
                           class="form-control @error('event_date') is-invalid @enderror" 
                           required 
                           aria-describedby="@error('event_date') event_date-error @enderror">
                    @error('event_date')
                        <div id="event_date-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 7. Nama Pelapor --}}
                <div class="form-group">
                    <label for="reporter_name" class="form-label">
                        Nama Anda <span class="wajib">(wajib)</span>
                    </label>
                    <input type="text" 
                           id="reporter_name" 
                           name="reporter_name" 
                           value="{{ old('reporter_name') }}" 
                           autocomplete="name" 
                           class="form-control @error('reporter_name') is-invalid @enderror" 
                           placeholder="Nama lengkap Anda" 
                           required 
                           aria-describedby="@error('reporter_name') reporter_name-error @enderror">
                    @error('reporter_name')
                        <div id="reporter_name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 8. Kontak Pelapor --}}
                <div class="form-group mb-4">
                    <label for="reporter_contact" class="form-label">
                        Kontak Anda <span class="wajib">(wajib)</span>
                    </label>
                    <input type="text" 
                           id="reporter_contact" 
                           name="reporter_contact" 
                           value="{{ old('reporter_contact') }}" 
                           inputmode="tel" 
                           class="form-control @error('reporter_contact') is-invalid @enderror" 
                           placeholder="Nomor WhatsApp (contoh: 081234567890) atau email" 
                           required 
                           aria-describedby="contact-note @error('reporter_contact') reporter_contact-error @enderror">
                    <span id="contact-note" class="form-keterangan">
                        Kontak hanya dilihat petugas kampus, tidak ditampilkan di website.
                    </span>
                    @error('reporter_contact')
                        <div id="reporter_contact-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Utama --}}
                <button type="submit" class="btn btn-tk-utama w-100">
                    Kirim laporan
                </button>
            </form>
        </div>
    </div>
@endsection

