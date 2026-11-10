<aside class="bar-aksi-bawah" aria-label="Aksi cepat lapor">
    <a href="{{ route('report.create', ['type' => 'lost']) }}" class="bar-aksi-bawah__btn bar-aksi-bawah__btn--hilang">
        Saya kehilangan
    </a>
    <a href="{{ route('report.create', ['type' => 'found']) }}" class="bar-aksi-bawah__btn bar-aksi-bawah__btn--ditemukan">
        Saya menemukan
    </a>
</aside>
