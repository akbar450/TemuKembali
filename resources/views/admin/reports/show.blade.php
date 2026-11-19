@extends('layouts.admin')

@section('title', 'Detail Laporan ' . $report->code)

@php
    $contact = trim($report->reporter_contact);
    $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
    $waNumber = null;
    if (!$isEmail) {
        $digits = preg_replace('/[^0-9]/', '', $contact);
        if (str_starts_with($digits, '0')) {
            $waNumber = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '62')) {
            $waNumber = $digits;
        } else {
            $waNumber = $digits;
        }
    }
@endphp

@section('content')
    <div class="tk-container--narrow mx-auto">
        <a href="{{ route('admin.reports.index') }}" class="d-inline-block mb-3 text-decoration-none" style="font-size: 14px; font-weight: 600; color: var(--biru);">
            ← Kembali ke daftar laporan
        </a>

        {{-- Header Ringkasan --}}
        <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 18px 20px; margin-bottom: 24px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="kode-laporan" style="font-size: 1.15rem;">{{ $report->code }}</span>
                <x-badge-status :status="$report->status" />
            </div>
            <h1 style="font-size: 1.35rem; margin-bottom: 0;">{{ $report->item_name }}</h1>
        </div>

        {{-- Bagian Pelapor (Hanya Terlihat Oleh Admin Sesuai PRD) --}}
        <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 18px 20px; margin-bottom: 24px;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 12px;">Informasi pelapor & verifikasi</h2>
            <div class="tanda-terima-list p-0 border-0 mb-0">
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">PIN Verifikasi (Anti-Klaim Palsu)</span>
                    <span class="tanda-terima-value">
                        <span class="kode-laporan" style="font-size: 1.15rem; color: var(--biru); background: rgba(42, 111, 134, 0.08); padding: 2px 8px; border-radius: 4px;">
                            {{ $report->claim_pin ?? 'Belum ada PIN' }}
                        </span>
                    </span>
                </div>
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">Nama pelapor</span>
                    <span class="tanda-terima-value">{{ $report->reporter_name }}</span>
                </div>
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">Peran pelapor</span>
                    <span class="tanda-terima-value">{{ $report->reporter_role_label }}</span>
                </div>
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">NIM / NIK</span>
                    <span class="tanda-terima-value">{{ $report->reporter_id_number ?? '-' }}</span>
                </div>
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">Kontak pelapor</span>
                    <span class="tanda-terima-value">
                        @if ($isEmail)
                            <a href="mailto:{{ $contact }}" style="font-weight: 600;">{{ $contact }}</a>
                        @elseif ($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" style="font-weight: 600;">
                                {{ $contact }} (Hubungi WhatsApp)
                            </a>
                        @else
                            <span>{{ $contact }}</span>
                        @endif
                    </span>
                </div>
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">Waktu laporan</span>
                    <span class="tanda-terima-value">{{ $report->created_at->translatedFormat('j F Y, H:i') }} WITA</span>
                </div>
                @if ($report->returned_at)
                    <div class="tanda-terima-row">
                        <span class="tanda-terima-label">Waktu dikembalikan</span>
                        <span class="tanda-terima-value">{{ $report->returned_at->translatedFormat('j F Y, H:i') }} WITA</span>
                    </div>
                @endif
            </div>

            @if ($report->secret_note)
                <div class="mt-3 p-3" style="background: rgba(242, 178, 76, 0.12); border: 1.5px dashed var(--tag-hilang); border-radius: var(--r-kontrol);">
                    <div style="font-weight: 700; font-size: 13px; color: var(--tinta); margin-bottom: 4px;">
                        🔍 Ciri Rahasia untuk Uji Kepemilikan (Tidak Terlihat di Web Publik):
                    </div>
                    <div style="font-size: 14px; color: var(--tinta); line-height: 1.5;">
                        {{ $report->secret_note }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Bagian Ubah Status (PRD Section 6) --}}
        <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 18px 20px; margin-bottom: 24px;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 12px;">Ubah status laporan</h2>
            
            @if ($report->status === 'dikembalikan')
                <p style="color: var(--tinta-redup); font-size: 14px; margin-bottom: 0;">
                    Status barang ini telah <strong>dikembalikan</strong> kepada pemiliknya dan tidak dapat diubah lagi.
                </p>
            @elseif (empty($allowedTransitions))
                <p style="color: var(--tinta-redup); font-size: 14px; margin-bottom: 0;">
                    Tidak ada perubahan status yang tersedia.
                </p>
            @else
                <form action="{{ route('admin.reports.update-status', $report->code) }}" method="POST" id="status-form">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status berikutnya</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="">Pilih status</option>
                            @foreach ($allowedTransitions as $trans)
                                <option value="{{ $trans }}">
                                    {{ ucfirst($trans) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-tk-utama" id="btn-simpan-status">
                        Simpan status
                    </button>
                </form>
            @endif
        </div>

        {{-- Bagian Ubah Isi Laporan (Perbaikan Data) --}}
        <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 18px 20px; margin-bottom: 32px;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Ubah isi laporan</h2>

            <form action="{{ route('admin.reports.update', $report->code) }}" method="POST" data-prevent-double-submit>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="item_name" class="form-label">Nama barang <span class="wajib">(wajib)</span></label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name', $report->item_name) }}" 
                           class="form-control @error('item_name') is-invalid @enderror" 
                           required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Kategori <span class="wajib">(wajib)</span></label>
                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $report->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Ciri-ciri barang <span class="wajib">(wajib)</span></label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control @error('description') is-invalid @enderror" 
                              rows="4" 
                              required>{{ old('description', $report->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="secret_note" class="form-label">Ciri rahasia / verifikasi <span class="wajib">(hanya admin)</span></label>
                    <textarea id="secret_note" 
                              name="secret_note" 
                              class="form-control @error('secret_note') is-invalid @enderror" 
                              rows="2">{{ old('secret_note', $report->secret_note) }}</textarea>
                    @error('secret_note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Lokasi <span class="wajib">(wajib)</span></label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $report->location) }}" 
                           class="form-control @error('location') is-invalid @enderror" 
                           required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="event_date" class="form-label">Tanggal kejadian <span class="wajib">(wajib)</span></label>
                    <input type="date" 
                           id="event_date" 
                           name="event_date" 
                           value="{{ old('event_date', $report->event_date ? $report->event_date->format('Y-m-d') : '') }}" 
                           max="{{ date('Y-m-d') }}" 
                           class="form-control @error('event_date') is-invalid @enderror" 
                           required>
                    @error('event_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reporter_name" class="form-label">Nama pelapor <span class="wajib">(wajib)</span></label>
                    <input type="text" 
                           id="reporter_name" 
                           name="reporter_name" 
                           value="{{ old('reporter_name', $report->reporter_name) }}" 
                           class="form-control @error('reporter_name') is-invalid @enderror" 
                           required>
                    @error('reporter_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reporter_role" class="form-label">Peran pelapor</label>
                    <select id="reporter_role" 
                            name="reporter_role" 
                            class="form-select @error('reporter_role') is-invalid @enderror">
                        <option value="">Belum ditentukan</option>
                        <option value="mahasiswa" {{ old('reporter_role', $report->reporter_role) === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ old('reporter_role', $report->reporter_role) === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="staf" {{ old('reporter_role', $report->reporter_role) === 'staf' ? 'selected' : '' }}>Staf</option>
                        <option value="tamu" {{ old('reporter_role', $report->reporter_role) === 'tamu' ? 'selected' : '' }}>Tamu</option>
                    </select>
                    @error('reporter_role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reporter_id_number" class="form-label">NIM / NIK</label>
                    <input type="text" 
                           id="reporter_id_number" 
                           name="reporter_id_number" 
                           value="{{ old('reporter_id_number', $report->reporter_id_number) }}" 
                           class="form-control @error('reporter_id_number') is-invalid @enderror" 
                           placeholder="NIM atau NIK pelapor">
                    @error('reporter_id_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="reporter_contact" class="form-label">Kontak pelapor <span class="wajib">(wajib)</span></label>
                    <input type="text" 
                           id="reporter_contact" 
                           name="reporter_contact" 
                           value="{{ old('reporter_contact', $report->reporter_contact) }}" 
                           class="form-control @error('reporter_contact') is-invalid @enderror" 
                           required>
                    @error('reporter_contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-tk-utama">
                    Simpan perubahan
                </button>
            </form>
        </div>

        {{-- Zona Bahaya: Hapus Laporan (Section 6.8 & 5.7) --}}
        <div style="border-top: 1px solid var(--garis); padding-top: 24px; margin-bottom: 40px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 style="font-size: 1rem; color: var(--bahaya); margin-bottom: 4px;">Zona bahaya</h3>
                    <p style="font-size: 13px; color: var(--tinta-redup); margin-bottom: 0;">
                        Laporan ini akan dihapus secara permanen dari sistem.
                    </p>
                </div>
                <button type="button" class="btn btn-tk-bahaya" data-dialog-target="dialog-hapus">
                    Hapus laporan
                </button>
            </div>
        </div>
    </div>

    {{-- Dialog Konfirmasi Hapus Laporan (Section 5.7) --}}
    <x-dialog-konfirmasi 
        id="dialog-hapus"
        title="Hapus laporan ini?"
        message="Laporan yang sudah dihapus tidak dapat dipulihkan kembali."
        actionText="Hapus laporan"
        actionRoute="{{ route('admin.reports.destroy', $report->code) }}"
        method="DELETE"
        :isDanger="true" />
@endsection

