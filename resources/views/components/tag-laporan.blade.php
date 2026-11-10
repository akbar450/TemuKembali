@props(['report'])

@php
    $statusText = match($report->status) {
        'hilang' => 'Hilang',
        'ditemukan' => 'Ditemukan',
        'dikembalikan' => 'Dikembalikan',
        default => ucfirst($report->status),
    };
    $formattedDate = $report->event_date ? $report->event_date->translatedFormat('j F Y') : '';
@endphp

<a href="{{ route('report.show', $report->code) }}" 
   class="tag tag--{{ $report->status }}" 
   aria-label="{{ $statusText }}, {{ $report->item_name }}, {{ $report->location }}">
    <div class="tag__stub">
        <span class="tag__status">{{ $statusText }}</span>
        <span class="tag__code">{{ $report->code }}</span>
    </div>
    <div class="tag__body">
        <h3 class="tag__title">{{ $report->item_name }}</h3>
        <span class="tag__category">{{ $report->category->name ?? 'Lainnya' }}</span>
        <span class="tag__location">{{ $report->location }}</span>
        <span class="tag__date">{{ $formattedDate }}</span>
    </div>
</a>

