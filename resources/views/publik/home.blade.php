@extends('layouts.publik')

@section('body_class', 'has-bar-aksi-bawah')

@section('content')
    <section class="mb-4">
        <h1 class="mb-3">Barang hilang di kampus?<br>Cek dulu di sini.</h1>

        <form action="{{ route('report.index') }}" method="GET" class="mb-3" role="search">
            <label for="search-home" class="visually-hidden">Cari nama barang atau ciri-cirinya</label>
            <input type="search" 
                   id="search-home" 
                   name="q" 
                   class="form-control" 
                   placeholder="Cari nama barang atau ciri-cirinya" 
                   style="min-height: 52px; font-size: 16px;">
        </form>

        <x-chip-kategori :categories="$categories" />
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Baru dilaporkan</h2>
            <a href="{{ route('report.index') }}" class="fw-semibold" style="font-size: 15px;">Lihat semua</a>
        </div>

        @if ($latestReports->isEmpty())
            <div class="text-center py-4" style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag);">
                <p class="mb-3" style="color: var(--tinta-redup);">Belum ada laporan.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('report.create', ['type' => 'lost']) }}" class="btn btn-header-lapor btn-header-lapor--hilang" style="min-height: 44px; padding: 0 16px;">Saya kehilangan</a>
                    <a href="{{ route('report.create', ['type' => 'found']) }}" class="btn btn-header-lapor btn-header-lapor--ditemukan" style="min-height: 44px; padding: 0 16px;">Saya menemukan</a>
                </div>
            </div>
        @else
            <div class="tag-grid mb-3">
                @foreach ($latestReports as $report)
                    <x-tag-laporan :report="$report" />
                @endforeach
            </div>

            <a href="{{ route('report.index') }}" class="btn btn-tk-sekunder w-100">
                Lihat semua laporan
            </a>
        @endif
    </section>

    <section class="mb-4">
        <h2>Cara kerja</h2>
        <div class="langkah-list">
            <div class="langkah-card">
                <div class="langkah-nomor">1</div>
                <div class="langkah-judul">Laporkan</div>
                <p class="langkah-desc">Isi form laporan barang hilang atau temuan Anda tanpa perlu mendaftar akun.</p>
            </div>
            <div class="langkah-card">
                <div class="langkah-nomor">2</div>
                <div class="langkah-judul">Datang ke pos bawa kode</div>
                <p class="langkah-desc">Simpan kode laporan Anda dan tunjukkan kepada petugas kampus di pos keamanan.</p>
            </div>
            <div class="langkah-card">
                <div class="langkah-nomor">3</div>
                <div class="langkah-judul">Barang diserahkan</div>
                <p class="langkah-desc">Petugas memverifikasi kecocokan barang dan memperbarui status menjadi dikembalikan.</p>
            </div>
        </div>
    </section>
@endsection

@section('bottom_bar')
    <x-bar-aksi-bawah />
@endsection
