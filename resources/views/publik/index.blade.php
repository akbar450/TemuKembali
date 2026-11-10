@extends('layouts.publik')

@section('title', 'Daftar Laporan')
@section('body_class', 'has-bar-aksi-bawah')

@section('content')
    <h1 class="mb-3">Semua laporan</h1>

    <form action="{{ route('report.index') }}" method="GET" class="mb-3" role="search">
        @if ($activeCategory)
            <input type="hidden" name="kategori" value="{{ $activeCategory }}">
        @endif
        @if ($activeStatus)
            <input type="hidden" name="status" value="{{ $activeStatus }}">
        @endif
        <label for="search-input" class="visually-hidden">Cari nama barang atau ciri-cirinya</label>
        <input type="search" 
               id="search-input" 
               name="q" 
               value="{{ $keyword }}" 
               class="form-control" 
               placeholder="Cari nama barang atau ciri-cirinya" 
               style="min-height: 52px; font-size: 16px;">
    </form>

    <x-chip-kategori :categories="$categories" :activeCategory="$activeCategory" />

    <x-segmen-status :activeStatus="$activeStatus" />

    <div class="d-flex justify-content-between align-items-center mb-3">
        <span style="font-size: 15px; font-weight: 600; color: var(--tinta-redup);">
            {{ $reports->total() }} laporan
        </span>
        @if ($keyword || $activeCategory || $activeStatus)
            <a href="{{ route('report.index') }}" class="small text-decoration-none" style="color: var(--biru);">
                Atur ulang filter
            </a>
        @endif
    </div>

    @if ($reports->isEmpty())
        <div class="text-center py-5 my-3" style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag);">
            @if ($keyword || $activeCategory || $activeStatus)
                <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 6px;">Belum ada laporan yang cocok.</h2>
                <p style="color: var(--tinta-redup); margin-bottom: 16px;">Coba kata lain atau pilih Semua.</p>
                <a href="{{ route('report.index') }}" class="btn btn-tk-sekunder">Atur ulang filter</a>
            @else
                <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 6px;">Belum ada laporan.</h2>
                <p style="color: var(--tinta-redup); margin-bottom: 16px;">Jadilah yang pertama melaporkan barang hilang atau temuan di kampus.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('report.create', ['type' => 'lost']) }}" class="btn btn-header-lapor btn-header-lapor--hilang" style="min-height: 44px; padding: 0 16px;">Saya kehilangan</a>
                    <a href="{{ route('report.create', ['type' => 'found']) }}" class="btn btn-header-lapor btn-header-lapor--ditemukan" style="min-height: 44px; padding: 0 16px;">Saya menemukan</a>
                </div>
            @endif
        </div>
    @else
        <div class="tag-grid mb-4">
            @foreach ($reports as $report)
                <x-tag-laporan :report="$report" />
            @endforeach
        </div>

        @if ($reports->hasPages())
            <nav class="pagination-nav" aria-label="Navigasi halaman laporan">
                @if ($reports->onFirstPage())
                    <span class="btn btn-tk-sekunder" aria-disabled="true" style="opacity: 0.4; cursor: not-allowed;">Sebelumnya</span>
                @else
                    <a href="{{ $reports->previousPageUrl() }}" class="btn btn-tk-sekunder" rel="prev">Sebelumnya</a>
                @endif

                <span class="pagination-info">
                    Halaman {{ $reports->currentPage() }} dari {{ $reports->lastPage() }}
                </span>

                @if ($reports->hasMorePages())
                    <a href="{{ $reports->nextPageUrl() }}" class="btn btn-tk-sekunder" rel="next">Berikutnya</a>
                @else
                    <span class="btn btn-tk-sekunder" aria-disabled="true" style="opacity: 0.4; cursor: not-allowed;">Berikutnya</span>
                @endif
            </nav>
        @endif
    @endif
@endsection

@section('bottom_bar')
    <x-bar-aksi-bawah />
@endsection
