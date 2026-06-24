@extends('layouts.guest')

@section('title', 'Masuk — TutorKu')

@section('auth-content')

<div class="mb-4">
    <h3 class="fw-600 mb-1" style="font-size:1.375rem;color:#0f172a;">Masuk ke akun</h3>
    <p class="text-muted" style="font-size:.9rem;">Masukkan username atau email beserta password Anda.</p>
</div>

{{-- FORM --}}
<form method="POST" action="{{ route('login') }}" data-loading id="loginForm">
    @csrf

    {{-- Email / Username --}}
    <div class="mb-3">
        <label class="tk-form-label" for="login">Username atau Email</label>
        <div class="tk-input-group">
            <i class="bi bi-person tk-input-icon"></i>
            <input
                type="text"
                id="login"
                name="login"
                class="tk-form-control @error('login') is-invalid @enderror"
                placeholder="username atau nama@email.com"
                value="{{ old('login') }}"
                autocomplete="username"
                required
            >
        </div>
        @error('login')
            <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="tk-form-label mb-0" for="password">Password</label>
            {{-- <a href="{{ route('password.request') }}" class="tk-link" style="font-size:.8rem;"> --}}
            <a href="#" class="tk-link" style="font-size:.8rem;">
                Lupa password?
            </a>
        </div>
        <div class="tk-input-group">
            <i class="bi bi-lock tk-input-icon"></i>
            <input
                type="password"
                id="password"
                name="password"
                class="tk-form-control @error('password') is-invalid @enderror"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required
            >
            <button type="button" class="tk-password-toggle" tabindex="-1">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- Remember me --}}
    <div class="mb-4 d-flex align-items-center gap-2">
        <input class="form-check-input mt-0" type="checkbox" id="remember" name="remember"
               style="width:16px;height:16px;border-radius:4px;border-color:#cbd5e1;cursor:pointer;">
        <label class="form-check-label text-muted" for="remember" style="font-size:.875rem;cursor:pointer;">
            Ingat saya selama 30 hari
        </label>
    </div>

    {{-- General error (credentials mismatch) --}}
    @if($errors->has('credentials'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3 p-3" style="border-radius:var(--tk-radius);font-size:.875rem;">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <span>{{ $errors->first('credentials') }}</span>
        </div>
    @endif

    {{-- Submit --}}
    <button type="submit" class="tk-btn-primary">
        <i class="bi bi-box-arrow-in-right"></i>
        Masuk
    </button>
</form>

{{-- Divider --}}
<div class="d-flex align-items-center gap-2 my-4">
    <hr class="flex-1 m-0" style="border-color:#e2e8f0;">
    <span class="text-muted" style="font-size:.8rem;white-space:nowrap;">atau daftar sebagai</span>
    <hr class="flex-1 m-0" style="border-color:#e2e8f0;">
</div>

{{-- Register links --}}
<div class="d-flex gap-2">
    <a href="{{ route('register') }}?role=siswa" class="flex-1 text-center py-2 px-3 text-decoration-none"
       style="border:1px solid var(--tk-border);border-radius:var(--tk-radius);font-size:.875rem;color:var(--tk-text-muted);transition:all .15s;"
       onmouseover="this.style.borderColor='#bfdbfe';this.style.color='#1e40af'"
       onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
        <i class="bi bi-person me-1"></i>Daftar Siswa
    </a>
    <a href="{{ route('register') }}?role=tutor" class="flex-1 text-center py-2 px-3 text-decoration-none"
       style="border:1px solid var(--tk-border);border-radius:var(--tk-radius);font-size:.875rem;color:var(--tk-text-muted);transition:all .15s;"
       onmouseover="this.style.borderColor='#bfdbfe';this.style.color='#1e40af'"
       onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
        <i class="bi bi-mortarboard me-1"></i>Daftar Tutor
    </a>
</div>

@endsection
