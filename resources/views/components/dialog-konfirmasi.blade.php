@props(['id', 'title', 'message', 'actionText', 'actionRoute', 'method' => 'POST', 'isDanger' => false])

<dialog id="{{ $id }}" class="tk-dialog">
    <form method="POST" action="{{ $actionRoute }}" class="m-0">
        @csrf
        @if (!in_array(strtoupper($method), ['GET', 'POST']))
            @method($method)
        @endif

        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 8px;">{{ $title }}</h3>
        <p style="font-size: 15px; color: var(--tinta-redup); margin-bottom: 24px;">{{ $message }}</p>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-tk-sekunder" data-dialog-close>Batal</button>
            <button type="submit" class="btn {{ $isDanger ? 'btn-tk-bahaya' : 'btn-tk-utama' }}">
                {{ $actionText }}
            </button>
        </div>
    </form>
</dialog>
