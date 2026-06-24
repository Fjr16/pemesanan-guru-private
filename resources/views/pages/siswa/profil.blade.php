@extends('layouts.app')

@section('title', 'Profil Saya — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
    <div style="max-width:860px;margin:0 auto;padding:0 20px;">

        {{-- Header --}}
        <div style="margin-bottom:24px;">
            <h1 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 4px;">Profil Saya</h1>
            <p style="font-size:13px;color:#8890a8;margin:0;">Kelola informasi akun dan data pribadi Anda.</p>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;font-size:13px;margin-bottom:14px;" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:10px;"></button>
            </div>
        @endif

        <div style="display:grid;grid-template-columns:240px 1fr;gap:14px;">

            {{-- ── Kiri: Avatar ───────────────────────────── --}}
            <div>
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:24px;text-align:center;">
                    <div style="width:80px;height:80px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:600;margin:0 auto 12px;">
                        {{ strtoupper(substr($student->name ?? $user->username, 0, 2)) }}
                    </div>
                    <div style="font-size:16px;font-weight:600;color:#1a1a2e;margin-bottom:2px;">{{ $student->name ?? $user->username }}</div>
                    <div style="font-size:12px;color:#8890a8;margin-bottom:12px;">{{ $user->email }}</div>
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:500;padding:4px 12px;border-radius:16px;">
                        <i class="bi bi-person-fill" style="font-size:10px;"></i> Siswa
                    </span>

                    <hr style="border:none;border-top:1px solid #f0f2f8;margin:16px 0;">

                    <div style="font-size:12px;color:#8890a8;">
                        Bergabung {{ $user->created_at->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>

            {{-- ── Kanan: Form ───────────────────────────── --}}
            <div>
                {{-- Info Akun --}}
                <form method="POST" action="{{ route('siswa.profil.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                            <i class="bi bi-person" style="font-size:14px;color:#1e2d6b;"></i>
                            <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Informasi Akun</span>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Username</label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('username') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('username')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('email') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('email')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="grid-column:1/-1;">
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">No. HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('no_hp') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('no_hp')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Data Diri --}}
                    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                            <i class="bi bi-credit-card-2-front" style="font-size:14px;color:#1e2d6b;"></i>
                            <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Data Diri</span>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                            <div style="grid-column:1/-1;">
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('name') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('name')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tempat Lahir</label>
                                <input type="text" name="tempat_lhr" value="{{ old('tempat_lhr', $student->tempat_lhr ?? '') }}"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('tempat_lhr') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('tempat_lhr')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lhr" value="{{ old('tanggal_lhr', $student->tanggal_lhr ?? '') }}"
                                       class="tanggal-input"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('tanggal_lhr') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('tanggal_lhr')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="grid-column:1/-1;">
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Alamat</label>
                                <textarea name="alamat" rows="2"
                                          style="width:100%;padding:8px 12px;border:1px solid {{ $errors->has('alamat') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;resize:vertical;">{{ old('alamat', $student->alamat ?? '') }}</textarea>
                                @error('alamat')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:8px;">
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s;">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

                {{-- Ubah Password --}}
                <form method="POST" action="{{ route('siswa.profil.password') }}" style="margin-top:14px;">
                    @csrf
                    @method('PUT')

                    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                            <i class="bi bi-shield-lock" style="font-size:14px;color:#1e2d6b;"></i>
                            <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Ubah Password</span>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Password Saat Ini</label>
                                <input type="password" name="current_password"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('current_password') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('current_password')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Password Baru</label>
                                <input type="password" name="password"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('password') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                @error('password')
                                    <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Konfirmasi</label>
                                <input type="password" name="password_confirmation"
                                       style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                            </div>
                        </div>

                        <div style="display:flex;justify-content:flex-end;margin-top:14px;">
                            <button type="submit"
                                    style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#1e2d6b;transition:background .15s;">
                                <i class="bi bi-key"></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection
