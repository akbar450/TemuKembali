@extends('layouts.publik')

@section('title', 'Sesi Berakhir')

@section('content')
    <div class="tk-container--narrow mx-auto text-center py-5">
        <h1 style="font-size: 2rem; margin-bottom: 8px;">419 — Sesi telah berakhir</h1>
        <p style="color: var(--tinta-redup); font-size: 15px; margin-bottom: 24px;">
            Halaman sudah terlalu lama terbuka. Silakan muat ulang halaman dan coba kembali.
        </p>
        <a href="{{ route('home') }}" class="btn btn-tk-utama">
            Kembali ke beranda
        </a>
    </div>
@endsection
