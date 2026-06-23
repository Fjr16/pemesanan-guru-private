@extends('layouts.tutor')

@section('title', 'Profil Saya — TutorKu')
@section('page-title', 'Profil Saya')

@section('content')

<div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;">

    {{-- ── Kiri: Avatar & stats ────────────────────────────── --}}
    <div>
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:24px;text-align:center;">
            <div style="width:80px;height:80px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:600;margin:0 auto 12px;">
                {{ strtoupper(substr(Auth::user()->name ?? 'T', 0, 2)) }}
            </div>
            <div style="font-size:16px;font-weight:600;color:#1a1a2e;margin-bottom:2px;">{{ Auth::user()->name ?? 'Tutor' }}</div>
            <div style="font-size:12px;color:#8890a8;margin-bottom:12px;">{{ Auth::user()->email ?? '' }}</div>
            <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:4px 12px;border-radius:16px;">
                <i class="bi bi-patch-check-fill" style="font-size:10px;"></i> Terverifikasi
            </span>

            <hr style="border:none;border-top:1px solid #f0f2f8;margin:16px 0;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <div style="font-size:18px;font-weight:600;color:#1a1a2e;">{{ $stats['total_sesi'] ?? 48 }}</div>
                    <div style="font-size:11px;color:#8890a8;">Total Sesi</div>
                </div>
                <div>
                    <div style="font-size:18px;font-weight:600;color:#1a1a2e;">{{ number_format($stats['avg_rating'] ?? 4.8, 1) }}</div>
                    <div style="font-size:11px;color:#8890a8;">Rating</div>
                </div>
                <div>
                    <div style="font-size:18px;font-weight:600;color:#1a1a2e;">{{ $stats['total_siswa'] ?? 32 }}</div>
                    <div style="font-size:11px;color:#8890a8;">Siswa</div>
                </div>
                <div>
                    <div style="font-size:18px;font-weight:600;color:#1a1a2e;">{{ $stats['bulan_aktif'] ?? 6 }}</div>
                    <div style="font-size:11px;color:#8890a8;">Bulan Aktif</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Kanan: Form profil ─────────────────────────────── --}}
    <div>
        <form method="POST" action="#">
            @csrf
            @method('PUT')

            {{-- Info dasar --}}
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                    <i class="bi bi-person" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Informasi Dasar</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name ?? '' }}" readonly
                               style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#f8f9fc;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Email</label>
                        <input type="email" value="{{ Auth::user()->email ?? '' }}" readonly
                               style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#f8f9fc;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">No. HP</label>
                        <input type="text" value="{{ Auth::user()->no_hp ?? '' }}" readonly
                               style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#f8f9fc;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Jenis Kelamin</label>
                        <input type="text" value="{{ $profile['jenis_kelamin'] ?? 'Pria' }}" readonly
                               style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#f8f9fc;outline:none;">
                    </div>
                </div>
            </div>

            {{-- Profil mengajar --}}
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                    <i class="bi bi-mortarboard" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Profil Mengajar</span>
                </div>

                <div style="margin-bottom:14px;">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Mata Pelajaran</label>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($profile['subjects'] ?? ['Matematika','Fisika'] as $subj)
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:500;padding:4px 12px;border-radius:16px;">
                                {{ $subj }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tarif per Jam</label>
                        <input type="text" value="Rp {{ number_format($profile['hourly_rate'] ?? 150000, 0, ',', '.') }}"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1e2d6b;font-weight:600;background:#fff;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Lokasi Mengajar</label>
                        <select style="width:100%;height:36px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                            <option {{ ($profile['lokasi'] ?? '') === 'offline' ? 'selected' : '' }}>Offline</option>
                            <option {{ ($profile['lokasi'] ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                            <option {{ ($profile['lokasi'] ?? '') === 'fleksibel' ? 'selected' : '' }}>Fleksibel</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top:14px;">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Deskripsi</label>
                    <textarea rows="3" style="width:100%;padding:8px 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;resize:vertical;">{{ $profile['desc'] ?? 'Saya adalah tutor berpengalaman dengan spesialisasi Matematika dan Fisika. Telah mengajar selama 5 tahun dengan ratusan siswa yang puas.' }}</textarea>
                </div>
            </div>

            {{-- Pendidikan --}}
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                    <i class="bi bi-book" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Riwayat Pendidikan</span>
                </div>

                @foreach($profile['education'] ?? [
                    ['sekolah'=>'Universitas Indonesia','jurusan'=>'Matematika','periode'=>'2014-2018'],
                    ['sekolah'=>'SMA Negeri 1 Jakarta','jurusan'=>'IPA','periode'=>'2011-2014'],
                ] as $edu)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                        <div style="width:36px;height:36px;border-radius:8px;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $edu['sekolah'] }}</div>
                            <div style="font-size:12px;color:#8890a8;">{{ $edu['jurusan'] }} · {{ $edu['periode'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="tk-topbar-btn" style="color:#4b5574">Batal</button>
                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s;">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
