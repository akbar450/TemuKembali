@extends('layouts.publik')

@section('title', 'Masuk Petugas')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="min-height: 60vh;">
        <div style="max-width: 400px; width: 100%;">
            <div style="background: var(--kartu); border: 1px solid var(--garis); border-radius: var(--r-tag); padding: 28px 24px;">
                <h1 style="font-size: 1.5rem; margin-bottom: 6px;">Masuk admin</h1>
                <p style="color: var(--tinta-redup); font-size: 14px; margin-bottom: 20px;">
                    Akses khusus petugas kampus pengelola barang hilang dan temuan.
                </p>

                @if ($errors->any())
                    <div class="notifikasi notifikasi--error" role="alert" style="margin-bottom: 16px; padding: 10px 14px; font-size: 14px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST" data-prevent-double-submit>
                    @csrf

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               class="form-control @error('email') is-invalid @enderror" 
                               required 
                               autofocus 
                               autocomplete="username">
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               required 
                               autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn btn-tk-utama w-100">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
