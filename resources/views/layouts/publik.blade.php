<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#EDF1F2">
    <title>{{ isset($title) ? $title . ' — ' : '' }}TemuKembali</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>
<body class="@yield('body_class')">
    <header class="tk-header">
        <div class="tk-container tk-header__inner">
            <a href="{{ route('home') }}" class="tk-brand">TemuKembali</a>
            <div class="tk-header__actions">
                <a href="{{ route('report.create', ['type' => 'lost']) }}" class="btn-header-lapor btn-header-lapor--hilang">Saya kehilangan</a>
                <a href="{{ route('report.create', ['type' => 'found']) }}" class="btn-header-lapor btn-header-lapor--ditemukan">Saya menemukan</a>
            </div>
        </div>
    </header>

    <main class="tk-container my-4 @yield('main_class')">
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
            <span>TemuKembali — Pos Pelaporan Barang Hilang dan Temuan Kampus</span>
            <a href="{{ route('admin.login') }}" class="text-decoration-none" style="color: var(--tinta-redup); font-size: 13px;">Masuk admin</a>
        </div>
    </footer>

    @yield('bottom_bar')

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

