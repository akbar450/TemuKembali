@extends('layouts.publik')

@section('title', 'Laporan Terkirim — Bukti Kode ' . $report->code)

@section('content')
    <div class="tk-container--narrow mx-auto text-center py-4 d-print-none">
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
            <button type="button" class="btn btn-tk-utama" onclick="cetakBuktiPdf()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align: -2px;">
                    <path d="M6 9V2h12v7"></path>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <path d="M6 14h12v8H6z"></path>
                </svg>
                Unduh / Cetak Bukti (PDF)
            </button>
            <button type="button" class="btn btn-tk-sekunder" data-copy-code="Kode: {{ $report->code }} | PIN: {{ $report->claim_pin }}">
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

    {{-- Lembar Cetak Dokumen Resmi PDF / Fisik untuk Satpam (Hanya tampil saat Cetak / Simpan PDF) --}}
    <div class="lembar-cetak-pdf">
        <div class="lembar-cetak-header">
            <div class="lembar-cetak-instansi">
                <h2 style="font-size: 18pt; margin: 0; font-weight: 800; letter-spacing: -0.5px;">TemuKembali</h2>
                <div style="font-size: 10pt; color: #444;">Pos Pelaporan Barang Hilang & Temuan Kampus</div>
            </div>
            <div class="lembar-cetak-badge">
                BUKTI RESMI PELAPORAN
            </div>
        </div>

        <hr style="border: 0; border-top: 2px solid #222; margin: 12px 0 16px 0;">

        {{-- Box Kode & PIN --}}
        <div class="lembar-cetak-box-kode">
            <div style="flex: 1; border-right: 2px dashed #999; padding-right: 16px;">
                <div style="font-size: 9pt; text-transform: uppercase; color: #555; font-weight: 700;">Kode Laporan</div>
                <div style="font-size: 24pt; font-weight: 800; font-family: monospace; letter-spacing: 2px; color: #000;">
                    {{ $report->code }}
                </div>
            </div>
            <div style="flex: 1; padding-left: 16px;">
                <div style="font-size: 9pt; text-transform: uppercase; color: #555; font-weight: 700;">PIN Verifikasi Rahasia</div>
                <div style="font-size: 24pt; font-weight: 800; font-family: monospace; letter-spacing: 3px; color: #1b4d3e;">
                    {{ $report->claim_pin ?? '-' }}
                </div>
            </div>
        </div>

        <div style="margin: 12px 0 16px 0; padding: 8px 12px; background: #f3f4f6; border-left: 4px solid #2a6f97; font-size: 9.5pt; color: #222;">
            <strong>Petunjuk Satpam:</strong> Cocokkan <strong>Kode Laporan</strong> dan <strong>PIN Verifikasi</strong> ini dengan data sistem di pos, serta cocokkan identitas pelapor dengan kartu identitas fisik (KTM/KTP).
        </div>

        {{-- Tabel Data Laporan --}}
        <table class="lembar-cetak-table">
            <tr>
                <th colspan="2" style="background: #e9ecef; font-size: 10pt; text-align: left; padding: 6px 10px;">A. INFORMASI BARANG</th>
            </tr>
            <tr>
                <td style="width: 32%;">Jenis Laporan</td>
                <td><strong>{{ $report->status === 'hilang' ? 'Barang Hilang' : 'Barang Ditemukan' }}</strong></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><strong>{{ $report->item_name }}</strong></td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>{{ $report->category->name ?? 'Lainnya' }}</td>
            </tr>
            <tr>
                <td>Tanggal Kejadian</td>
                <td>{{ $report->event_date ? $report->event_date->translatedFormat('l, j F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Lokasi Kejadian</td>
                <td>{{ $report->location }}</td>
            </tr>
            <tr>
                <td>Ciri-ciri Barang</td>
                <td>{{ $report->description }}</td>
            </tr>
            @if ($report->secret_note)
            <tr>
                <td>Ciri Khusus Rahasia</td>
                <td><em style="color: #b00;">{{ $report->secret_note }}</em> <span style="font-size: 8.5pt; color: #666;">(Hanya ada di lembar pelapor)</span></td>
            </tr>
            @endif

            <tr>
                <th colspan="2" style="background: #e9ecef; font-size: 10pt; text-align: left; padding: 6px 10px;">B. IDENTITAS PELAPOR</th>
            </tr>
            <tr>
                <td>Nama Pelapor</td>
                <td><strong>{{ $report->reporter_name }}</strong></td>
            </tr>
            <tr>
                <td>Peran / Status</td>
                <td>{{ ucfirst($report->reporter_role) }}</td>
            </tr>
            <tr>
                <td>NIM / NIK</td>
                <td>{{ $report->reporter_id_number }}</td>
            </tr>
            <tr>
                <td>Kontak / WhatsApp</td>
                <td>{{ $report->reporter_contact }}</td>
            </tr>

            <tr>
                <th colspan="2" style="background: #e9ecef; font-size: 10pt; text-align: left; padding: 6px 10px;">C. POS LAYANAN KAMPUS</th>
            </tr>
            <tr>
                <td>Pos Pengambilan / Validasi</td>
                <td><strong>{{ $campusPostLocation ?? 'Pos Satpam Utama' }}</strong></td>
            </tr>
            <tr>
                <td>Waktu Pelaporan</td>
                <td>{{ $report->created_at->translatedFormat('j F Y, H:i') }} WITA</td>
            </tr>
        </table>

        {{-- Footer Lembar Cetak --}}
        <div class="lembar-cetak-footer">
            <div style="font-size: 8.5pt; color: #555;">
                Dicetak pada: {{ now()->translatedFormat('j F Y, H:i') }} WITA<br>
                Sistem Informasi TemuKembali — Komputasi Awan Kampus
            </div>
            <div style="text-align: right; width: 200px;">
                <div style="font-size: 9pt; margin-bottom: 45px;">Tanda Tangan Petugas Pos,</div>
                <div style="border-bottom: 1px solid #333;"></div>
                <div style="font-size: 8.5pt; color: #555; margin-top: 4px;">Petugas Satpam Jaga</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function cetakBuktiPdf() {
        const originalTitle = document.title;
        document.title = 'Bukti-Laporan-{{ $report->code }}';
        window.print();
        document.title = originalTitle;
    }
</script>
@endpush

