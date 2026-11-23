@extends('layouts.publik')

@section('title', 'Daftar Laporan')
@section('body_class', 'has-bar-aksi-bawah')

@section('content')
    <section class="mb-4">
        <h1 class="mb-3">Barang hilang di kampus?<br>Cek dulu di sini.</h1>

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
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            @if ($activeCategory)
                @php
                    $selectedCat = $categories->firstWhere('id', $activeCategory);
                @endphp
                <h2 class="mb-0">{{ $selectedCat ? $selectedCat->name : 'Semua laporan' }}</h2>
            @elseif ($keyword)
                <h2 class="mb-0">Pencarian "{{ $keyword }}"</h2>
            @else
                <h2 class="mb-0">Semua laporan</h2>
            @endif

            @if ($keyword || $activeCategory || $activeStatus)
                <a href="{{ route('report.index') }}" class="small text-decoration-none" style="color: var(--biru); font-weight: 600;">
                    Atur ulang filter
                </a>
            @endif
        </div>

        <x-segmen-status :activeStatus="$activeStatus" />

        <div class="mb-3">
            <span style="font-size: 15px; font-weight: 600; color: var(--tinta-redup);">
                {{ $reports->total() }} laporan
            </span>
        </div>

        @if ($reports->isEmpty())
            <div class="d-flex align-items-center justify-content-center text-center mb-3" 
                 style="min-height: 120px; background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 32px 20px;">
                <p class="mb-0 text-center" style="color: var(--tinta-redup); font-size: 15px;">
                    @if ($activeCategory)
                        Belum ada laporan untuk kategori ini.
                    @elseif ($keyword || $activeStatus)
                        Belum ada laporan yang cocok dengan filter.
                    @else
                        Belum ada laporan.
                    @endif
                </p>
            </div>
            @if ($keyword || $activeCategory || $activeStatus)
                <a href="{{ route('report.index') }}" class="btn btn-tk-sekunder w-100 mb-4">
                    Tampilkan semua
                </a>
            @else
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <a href="{{ route('report.create', ['type' => 'lost']) }}" class="btn btn-header-lapor btn-header-lapor--hilang" style="min-height: 44px; padding: 0 16px;">Saya kehilangan</a>
                    <a href="{{ route('report.create', ['type' => 'found']) }}" class="btn btn-header-lapor btn-header-lapor--ditemukan" style="min-height: 44px; padding: 0 16px;">Saya menemukan</a>
                </div>
            @endif
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
    </section>

    <x-cara-kerja />
@endsection

@section('bottom_bar')
    <x-bar-aksi-bawah />
@endsection
