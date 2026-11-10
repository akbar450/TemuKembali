<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#EDF1F2">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Admin TemuKembali</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>
<body>
    <header class="tk-header">
        <div class="tk-container tk-header__inner">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.reports.index') }}" class="tk-brand">TemuKembali Admin</a>
                <a href="{{ route('home') }}" target="_blank" class="text-decoration-none" style="font-size: 13px; color: var(--tinta-redup);">Buka website publik</a>
            </div>
            @auth
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-tk-sekunder" style="min-height: 38px; padding: 0 14px; font-size: 14px;">Keluar</button>
                </form>
            @endauth
        </div>
    </header>

    <main class="tk-container my-4">
        @if (session('sukses'))
            <div class="notifikasi notifikasi--sukses" role="alert">
                {{ session('sukses') }}
            </div>
        @endif

        @if (session('error'))
            <div class="notifikasi notifikasi--error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="tk-footer">
        <div class="tk-container tk-footer__inner">
            <span>Panel Pengelola Petugas Kampus</span>
            <span>Zona waktu: WITA</span>
        </div>
    </footer>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
