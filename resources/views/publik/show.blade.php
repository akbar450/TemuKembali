@extends('layouts.publik')

@section('title', 'Detail Laporan ' . $report->code)

@php
    $statusText = match($report->status) {
        'hilang' => 'Hilang',
        'ditemukan' => 'Ditemukan',
        'dikembalikan' => 'Dikembalikan',
        default => ucfirst($report->status),
    };
@endphp

@section('content')
    <div class="tk-container--narrow mx-auto">
        <a href="{{ route('report.index') }}" class="d-inline-block mb-3 text-decoration-none" style="font-size: 14px; font-weight: 600; color: var(--biru);">
            ← Kembali ke daftar laporan
        </a>

        {{-- Tag Header Ukuran Penuh (Section 5.6) --}}
        <div class="detail-tag-header tag--{{ $report->status }}">
            <div class="detail-tag-header__stub">
                <span style="font-size: 15px;">{{ $statusText }}</span>
                <span class="kode-laporan" style="font-size: 16px;">{{ $report->code }}</span>
            </div>
            <div class="detail-tag-header__body">
                <h1 style="font-size: 1.5rem; margin-bottom: 0;">{{ $report->item_name }}</h1>
            </div>
        </div>

        {{-- Kotak Petunjuk Pengambilan (Paling Penting, Langsung Terlihat) --}}
        <div class="kotak-petunjuk">
            <div class="kotak-petunjuk__title">Petunjuk pengambilan barang</div>
            <p class="kotak-petunjuk__text">
                Untuk mengambil atau menanyakan barang ini, datang ke <strong>{{ $campusPostLocation }}</strong> dan sebutkan kode <strong>{{ $report->code }}</strong> kepada petugas.
            </p>
        </div>

        {{-- Lembar Keterangan Tanda Terima --}}
        <div class="tanda-terima-list">
            <div class="tanda-terima-row">
                <span class="tanda-terima-label">Kategori</span>
                <span class="tanda-terima-value">{{ $report->category->name ?? 'Lainnya' }}</span>
            </div>
            <div class="tanda-terima-row">
                <span class="tanda-terima-label">Lokasi</span>
                <span class="tanda-terima-value">{{ $report->location }}</span>
            </div>
            <div class="tanda-terima-row">
                <span class="tanda-terima-label">Tanggal kejadian</span>
                <span class="tanda-terima-value">{{ $report->event_date ? $report->event_date->translatedFormat('j F Y') : '-' }}</span>
            </div>
            <div class="tanda-terima-row">
                <span class="tanda-terima-label">Dilaporkan pada</span>
                <span class="tanda-terima-value">{{ $report->created_at->translatedFormat('j F Y, H:i') }} WITA</span>
            </div>
            @if ($report->status === 'dikembalikan' && $report->returned_at)
                <div class="tanda-terima-row">
                    <span class="tanda-terima-label">Dikembalikan pada</span>
                    <span class="tanda-terima-value">{{ $report->returned_at->translatedFormat('j F Y, H:i') }} WITA</span>
                </div>
            @endif
        </div>

        {{-- Ciri-ciri Barang / Deskripsi --}}
        <div class="mb-4">
            <h2 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 8px;">Ciri-ciri barang</h2>
            <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 16px; font-size: 15px; line-height: 1.6; white-space: pre-line;">{{ $report->description }}</div>
        </div>
    </div>
@endsection
