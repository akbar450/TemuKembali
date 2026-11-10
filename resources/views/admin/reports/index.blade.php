@extends('layouts.admin')

@section('title', 'Daftar Laporan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 style="font-size: 1.5rem; margin-bottom: 0;">Kelola laporan</h1>
        <span style="font-size: 14px; font-weight: 600; color: var(--tinta-redup);">
            {{ $reports->total() }} total
        </span>
    </div>

    {{-- Filter & Pencarian --}}
    <form action="{{ route('admin.reports.index') }}" method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-12 col-md-5">
                <input type="search" 
                       name="q" 
                       value="{{ $keyword }}" 
                       class="form-control" 
                       placeholder="Cari kode, nama barang, atau nama pelapor...">
            </div>
            <div class="col-6 col-md-3">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)$activeCategory === (string)$cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="hilang" {{ $activeStatus === 'hilang' ? 'selected' : '' }}>Hilang</option>
                    <option value="ditemukan" {{ $activeStatus === 'ditemukan' ? 'selected' : '' }}>Ditemukan</option>
                    <option value="dikembalikan" {{ $activeStatus === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-tk-utama w-100" style="min-height: 48px;">Filter</button>
                @if ($keyword || $activeCategory || $activeStatus)
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-tk-sekunder" style="min-height: 48px;" title="Reset filter">×</a>
                @endif
            </div>
        </div>
    </form>

    @if ($reports->isEmpty())
        <div class="text-center py-5" style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag);">
            <p style="color: var(--tinta-redup); margin-bottom: 0;">Tidak ada laporan yang sesuai dengan filter.</p>
        </div>
    @else
        {{-- Tampilan Desktop (>= 900px): Tabel (Section 6.7) --}}
        <div class="d-none d-lg-block table-responsive mb-4">
            <table class="table-admin">
                <thead>
                    <tr>
                        <th scope="col" style="width: 120px;">Kode</th>
                        <th scope="col">Nama barang</th>
                        <th scope="col" style="width: 170px;">Kategori</th>
                        <th scope="col" style="width: 130px;">Status</th>
                        <th scope="col" style="width: 140px;">Tanggal</th>
                        <th scope="col" style="width: 80px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>
                                <span class="kode-laporan">{{ $report->code }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reports.show', $report->code) }}" class="fw-semibold text-decoration-none" style="color: var(--tinta);">
                                    {{ $report->item_name }}
                                </a>
                            </td>
                            <td style="color: var(--tinta-redup);">{{ $report->category->name ?? '-' }}</td>
                            <td>
                                <x-badge-status :status="$report->status" />
                            </td>
                            <td style="color: var(--tinta-redup); font-size: 13px;">
                                {{ $report->event_date ? $report->event_date->translatedFormat('j M Y') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.reports.show', $report->code) }}" class="btn btn-tk-sekunder" style="min-height: 34px; padding: 0 10px; font-size: 13px;">
                                    Buka
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tampilan Mobile (< 900px): Daftar Baris Ringkas (Section 6.7) --}}
        <div class="d-lg-none d-flex flex-column gap-2 mb-4">
            @foreach ($reports as $report)
                <a href="{{ route('admin.reports.show', $report->code) }}" 
                   class="d-block text-decoration-none" 
                   style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 14px; color: var(--tinta);">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="kode-laporan" style="font-size: 14px;">{{ $report->code }}</span>
                        <x-badge-status :status="$report->status" />
                    </div>
                    <div class="fw-bold mb-1" style="font-size: 15px;">{{ $report->item_name }}</div>
                    <div class="d-flex justify-content-between" style="font-size: 13px; color: var(--tinta-redup);">
                        <span>{{ $report->category->name ?? '-' }}</span>
                        <span>{{ $report->event_date ? $report->event_date->translatedFormat('j M Y') : '-' }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($reports->hasPages())
            <nav class="pagination-nav" aria-label="Navigasi halaman admin">
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

