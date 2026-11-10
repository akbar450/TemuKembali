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
                <div style="padding: 20px 16px; border-top: 2px dashed var(--garis-tegas);">
                    <div style="font-size: 13px; color: var(--tinta-redup); margin-bottom: 2px;">Kode Laporan</div>
                    <div class="kode-laporan" style="font-size: 2rem; font-weight: 700; color: var(--tinta); margin-bottom: 12px;">
                        {{ $report->code }}
                    </div>
                    @if ($report->claim_pin)
                        <div style="background: rgba(42, 111, 134, 0.08); border: 1px dashed var(--garis-tegas); border-radius: var(--r-kontrol); padding: 8px 12px; margin-bottom: 12px;">
                            <div style="font-size: 12px; color: var(--tinta-redup);">PIN Verifikasi Rahasia (Anti Klaim Palsu)</div>
                            <div class="kode-laporan" style="font-size: 1.5rem; font-weight: 700; color: var(--biru);">
                                {{ $report->claim_pin }}
                            </div>
                        </div>
                    @endif
                    <div style="font-size: 14px; font-weight: 600; color: var(--tinta-redup);">
                        {{ $report->item_name }}
                    </div>
                </div>
            </div>
        </div>

        <h1 style="font-size: 1.5rem; margin-bottom: 8px;">Laporan terkirim.</h1>
        <p style="color: var(--tinta-redup); font-size: 15px; margin-bottom: 24px; max-width: 460px; margin-left: auto; margin-right: auto;">
            Simpan <strong>Kode Laporan</strong> dan <strong>PIN Verifikasi</strong> ini. Petugas kampus akan meminta keduanya saat pencocokan barang di pos untuk membuktikan bahwa Anda pelapor sah.
        </p>

        <div class="d-flex flex-column gap-2" style="max-width: 320px; margin-left: auto; margin-right: auto;">
            <button type="button" class="btn btn-tk-utama" data-copy-code="Kode: {{ $report->code }} | PIN: {{ $report->claim_pin }}">
                Salin bukti (Kode & PIN)
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

