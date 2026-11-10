@extends('layouts.publik')

@section('title', 'Laporan Terkirim')

@section('content')
    <div class="tk-container--narrow mx-auto text-center py-4">
        {{-- Tag Besar dengan Animasi Berayun Tunggal (Section 6.5) --}}
        <div class="sukses-tag-anim d-inline-block mb-4" style="max-width: 380px; width: 100%;">
            <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); overflow: hidden; box-shadow: none;">
                <div style="background: {{ $report->status === 'hilang' ? 'var(--tag-hilang)' : 'var(--tag-ditemukan)' }}; padding: 12px; font-weight: 700; font-size: 14px; color: var(--tinta);">
                    Kode Bukti Laporan
                </div>
                <div style="padding: 24px 16px; border-top: 2px dashed var(--garis-tegas);">
                    <div class="kode-laporan" style="font-size: 2.25rem; font-weight: 700; color: var(--tinta); margin-bottom: 6px;">
                        {{ $report->code }}
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: var(--tinta-redup);">
                        {{ $report->item_name }}
                    </div>
                </div>
            </div>
        </div>

        <h1 style="font-size: 1.5rem; margin-bottom: 8px;">Laporan terkirim.</h1>
        <p style="color: var(--tinta-redup); font-size: 15px; margin-bottom: 24px; max-width: 440px; margin-left: auto; margin-right: auto;">
            Simpan kode laporan ini sebagai bukti. Petugas kampus akan memintanya saat pengambilan atau penyerahan barang.
        </p>

        <div class="d-flex flex-column gap-2" style="max-width: 320px; margin-left: auto; margin-right: auto;">
            <button type="button" class="btn btn-tk-utama" data-copy-code="{{ $report->code }}">
                Salin kode
            </button>
            <a href="{{ route('report.show', $report->code) }}" class="btn btn-tk-sekunder">
                Lihat laporan
            </a>
            <a href="{{ route('home') }}" class="mt-2 text-decoration-none" style="font-size: 14px; color: var(--biru);">
                Kembali ke beranda
            </a>
        </div>
    </div>
@endsection

