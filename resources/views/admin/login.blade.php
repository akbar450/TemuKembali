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
                               value="{{ old('email', $rememberedEmail ?? '') }}" 
                               class="form-control @error('email') is-invalid @enderror" 
                               required 
                               {{ empty($rememberedEmail) ? 'autofocus' : '' }} 
                               autocomplete="username">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               required 
                               {{ !empty($rememberedEmail) ? 'autofocus' : '' }} 
                               autocomplete="current-password">
                    </div>

                    <div class="form-check mb-4 d-flex align-items-center gap-2">
                        <input class="form-check-input mt-0" 
                               type="checkbox" 
                               name="remember" 
                               id="remember" 
                               value="1" 
                               {{ old('remember', !empty($rememberedEmail)) ? 'checked' : '' }}
                               style="cursor: pointer; width: 18px; height: 18px;">
                        <label class="form-check-label mb-0" for="remember" style="font-size: 14px; color: var(--tinta); cursor: pointer; user-select: none;">
                            Ingat saya (Simpan info login)
                        </label>
                    </div>

                    <button type="submit" class="btn btn-tk-utama w-100">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

