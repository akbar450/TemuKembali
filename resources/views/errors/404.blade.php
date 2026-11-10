@extends('layouts.publik')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <div class="tk-container--narrow mx-auto text-center py-5">
        <h1 style="font-size: 2rem; margin-bottom: 8px;">404 — Halaman tidak ditemukan</h1>
        <p style="color: var(--tinta-redup); font-size: 15px; margin-bottom: 24px;">
            Laporan atau halaman yang Anda cari tidak ada atau sudah dihapus.
        </p>
        <a href="{{ route('home') }}" class="btn btn-tk-utama">
            Kembali ke beranda
        </a>
    </div>
@endsection
