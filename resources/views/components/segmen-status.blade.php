@props(['activeStatus' => null])

<div class="segmen-status-container" role="group" aria-label="Filter status laporan">
    <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}" 
       class="segmen-status-item {{ empty($activeStatus) ? 'active' : '' }}">
        <span>Semua</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'hilang', 'page' => null]) }}" 
       class="segmen-status-item {{ $activeStatus === 'hilang' ? 'active' : '' }}">
        <span class="status-dot status-dot--hilang" aria-hidden="true"></span>
        <span>Hilang</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'ditemukan', 'page' => null]) }}" 
       class="segmen-status-item {{ $activeStatus === 'ditemukan' ? 'active' : '' }}">
        <span class="status-dot status-dot--ditemukan" aria-hidden="true"></span>
        <span>Ditemukan</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'dikembalikan', 'page' => null]) }}" 
       class="segmen-status-item {{ $activeStatus === 'dikembalikan' ? 'active' : '' }}">
        <span class="status-dot status-dot--dikembalikan" aria-hidden="true"></span>
        <span>Dikembalikan</span>
    </a>
</div>
