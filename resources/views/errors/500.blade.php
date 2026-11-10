@extends('layouts.publik')

@section('title', 'Kesalahan Server')

@section('content')
    <div class="tk-container--narrow mx-auto text-center py-5">
        <h1 style="font-size: 2rem; margin-bottom: 8px;">500 — Terjadi kesalahan pada server</h1>
        <p style="color: var(--tinta-redup); font-size: 15px; margin-bottom: 24px;">
            Laporan belum terkirim atau sistem sedang mengalami kendala. Periksa koneksi, lalu coba lagi.
        </p>
        <a href="{{ route('home') }}" class="btn btn-tk-utama">
            Kembali ke beranda
        </a>
    </div>
@endsection

