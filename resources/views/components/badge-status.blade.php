@props(['status'])

@php
    $text = match($status) {
        'hilang' => 'Hilang',
        'ditemukan' => 'Ditemukan',
        'dikembalikan' => 'Dikembalikan',
        default => ucfirst($status),
    };
@endphp

<span class="badge-status badge-status--{{ $status }}">{{ $text }}</span>
