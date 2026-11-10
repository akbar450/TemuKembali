@extends('layouts.publik')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="tk-container--narrow mx-auto text-center py-5">
        <h1 style="font-size: 2rem; margin-bottom: 8px;">403 — Akses ditolak</h1>
        <p style="color: var(--tinta-redup); font-size: 15px; margin-bottom: 24px;">
            Anda tidak memiliki izin untuk membuka halaman ini.
        </p>
        <a href="{{ route('home') }}" class="btn btn-tk-utama">
            Kembali ke beranda
        </a>
    </div>
@endsection

