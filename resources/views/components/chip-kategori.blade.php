@props(['categories', 'activeCategory' => null])

<div class="chip-scroll" role="navigation" aria-label="Filter kategori">
    <a href="{{ request()->fullUrlWithQuery(['kategori' => null, 'page' => null]) }}" 
       class="chip-kategori {{ empty($activeCategory) ? 'active' : '' }}">
        Semua
    </a>
    @foreach ($categories as $cat)
        <a href="{{ request()->fullUrlWithQuery(['kategori' => $cat->id, 'page' => null]) }}" 
           class="chip-kategori {{ (string)$activeCategory === (string)$cat->id ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>
