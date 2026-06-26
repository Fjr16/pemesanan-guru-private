@extends('layouts.tutor')

@section('title', 'Profil Saya — TutorKu')
@section('page-title', 'Profil Saya')

@section('content')

<div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;">

    {{-- ── Kiri: Avatar & stats ────────────────────────────── --}}
    <div>
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:24px;text-align:center;">
            @if($tutor && $tutor->foto)
                <img src="{{ asset('storage/' . $tutor->foto) }}" alt="Foto Profil"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 12px;display:block;border:2px solid #eef2ff;">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:600;margin:0 auto 12px;">
                    {{ strtoupper(substr($tutor->name ?? $user->username, 0, 2)) }}
                </div>
            @endif

            {{-- Upload foto --}}
            <form method="POST" action="{{ route('tutor.profil.photo') }}" enctype="multipart/form-data" id="photoForm" style="margin-bottom:12px;">
                @csrf
                @method('PUT')
                <label style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;background:#fff;color:#4b5574;transition:all .15s;">
                    <i class="bi bi-camera" style="font-size:12px;"></i> Ganti Foto
                    <input type="file" name="foto" accept="image/*" style="display:none;" onchange="document.getElementById('photoForm').submit()">
                </label>
            </form>
            <div style="font-size:16px;font-weight:600;color:#1a1a2e;margin-bottom:2px;">{{ $tutor->name ?? $user->username }}</div>
            <div style="font-size:12px;color:#8890a8;margin-bottom:12px;">{{ $user->email }}</div>
            @if($tutor && $tutor->status === 'active')
                <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:4px 12px;border-radius:16px;">
                    <i class="bi bi-patch-check-fill" style="font-size:10px;"></i> Terverifikasi
                </span>
            @elseif($tutor && $tutor->status === 'pending')
                <span style="display:inline-flex;align-items:center;gap:4px;background:#fef3c7;color:#b45309;font-size:11px;font-weight:500;padding:4px 12px;border-radius:16px;">
                    <i class="bi bi-hourglass-split" style="font-size:10px;"></i> Menunggu Verifikasi
                </span>
            @else
                <span style="display:inline-flex;align-items:center;gap:4px;background:#fee2e2;color:#dc2626;font-size:11px;font-weight:500;padding:4px 12px;border-radius:16px;">
                    <i class="bi bi-x-circle" style="font-size:10px;"></i> Ditolak
                </span>
            @endif

            <hr style="border:none;border-top:1px solid #f0f2f8;margin:16px 0;">

            <div style="text-align:left;">
                <div style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:8px;">Mata Pelajaran</div>
                @if($tutor && $tutor->tutorSubjects->count())
                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                        @foreach($tutor->tutorSubjects as $ts)
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">
                                {{ $ts->subjectCategory->name ?? '-' }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div style="font-size:12px;color:#8890a8;">Belum ada</div>
                @endif
            </div>

            <hr style="border:none;border-top:1px solid #f0f2f8;margin:16px 0;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <div style="font-size:18px;font-weight:600;color:#1a1a2e;">{{ $tutor?->orders()?->where('status','complete')->count() }}</div>
                    <div style="font-size:11px;color:#8890a8;">Sesi Selesai</div>
                </div>
                <div>
                    <div style="font-size:18px;font-weight:600;color:#1a1a2e;">{{ $tutor?->orders()?->count() }}</div>
                    <div style="font-size:11px;color:#8890a8;">Total Order</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Kanan: Form profil ─────────────────────────────── --}}
    <div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;font-size:13px;margin-bottom:14px;" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:10px;"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" style="border-radius:10px;font-size:13px;margin-bottom:14px;" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:10px;"></button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             INFORMASI AKUN & PROFIL TUTOR
        ═══════════════════════════════════════════════════ --}}
        <form method="POST" action="{{ route('tutor.profil.update') }}">
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
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('no_hp') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                        @error('no_hp')
                            <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                    <i class="bi bi-mortarboard" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Profil Tutor</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $tutor->name ?? '') }}"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('name') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                        @error('name')
                            <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" style="width:100%;height:36px;padding:0 10px;border:1px solid {{ $errors->has('jenis_kelamin') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                            <option value="Pria" {{ old('jenis_kelamin', $tutor->jenis_kelamin ?? '') === 'Pria' ? 'selected' : '' }}>Pria</option>
                            <option value="Wanita" {{ old('jenis_kelamin', $tutor->jenis_kelamin ?? '') === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lhr" value="{{ old('tanggal_lhr', $tutor->tanggal_lhr ?? '') }}"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('tanggal_lhr') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Domisili</label>
                        <input type="text" name="domisili" value="{{ old('domisili', $tutor->domisili ?? '') }}"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('domisili') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Pekerjaan</label>
                        <input type="text" name="job" value="{{ old('job', $tutor->job ?? '') }}"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('job') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tarif per Jam (Rp)</label>
                        <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $tutor->hourly_rate ?? '') }}" min="10000"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('hourly_rate') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                    </div>
                    <div style="grid-column:1/-1;">
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Lokasi Mengajar</label>
                        <div style="display:flex;gap:8px;">
                            @foreach(['offline' => 'Offline', 'online' => 'Online', 'fleksibel' => 'Fleksibel'] as $val => $label)
                                <label style="display:flex;align-items:center;gap:6px;padding:7px 16px;border:1px solid {{ old('lokasi_mengajar', $tutor->lokasi_mengajar ?? '') === $val ? '#3730a3' : '#e8eaf0' }};border-radius:8px;cursor:pointer;font-size:13px;color:{{ old('lokasi_mengajar', $tutor->lokasi_mengajar ?? '') === $val ? '#3730a3' : '#4b5574' }};background:{{ old('lokasi_mengajar', $tutor->lokasi_mengajar ?? '') === $val ? '#eef2ff' : '#fff' }};transition:all .15s;">
                                    <input type="radio" name="lokasi_mengajar" value="{{ $val }}" {{ old('lokasi_mengajar', $tutor->lokasi_mengajar ?? '') === $val ? 'checked' : '' }} style="display:none;">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Deskripsi</label>
                        <textarea name="desc" rows="3" style="width:100%;padding:8px 12px;border:1px solid {{ $errors->has('desc') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;resize:vertical;">{{ old('desc', $tutor->desc ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-bottom:14px;">
                <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s;">
                    <i class="bi bi-check-lg"></i> Simpan Profil
                </button>
            </div>
        </form>

        {{-- ═══════════════════════════════════════════════════
             MATA PELAJARAN
        ═══════════════════════════════════════════════════ --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-book" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Mata Pelajaran</span>
                </div>
                <button type="button" onclick="document.getElementById('addSubjectForm').style.display = document.getElementById('addSubjectForm').style.display === 'none' ? 'block' : 'none'"
                        style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#1e2d6b;transition:all .15s;">
                    <i class="bi bi-plus-lg" style="font-size:11px;"></i> Tambah
                </button>
            </div>

            {{-- Form tambah mata pelajaran --}}
            <div id="addSubjectForm" style="display:none;background:#f8fafc;border:1px solid #e8eaf0;border-radius:10px;padding:16px;margin-bottom:14px;">
                <form method="POST" action="{{ route('tutor.profil.subject.store') }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Mata Pelajaran</label>
                            <select name="subject_category_id" required style="width:100%;height:36px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                <option value="">-- Pilih --</option>
                                @foreach($mataPelajaran as $mp)
                                    <option value="{{ $mp->id }}">{{ $mp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tingkatan</label>
                            <div style="display:flex;flex-wrap:wrap;gap:6px;padding-top:2px;">
                                @foreach(['SD','SMP','SMA','Kuliah'] as $tingkat)
                                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;color:#4b5574;cursor:pointer;">
                                        <input type="checkbox" name="tingkatan[]" value="{{ $tingkat }}" style="width:14px;height:14px;cursor:pointer;">
                                        {{ $tingkat }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:6px;">
                        <button type="button" onclick="document.getElementById('addSubjectForm').style.display='none'" style="padding:6px 14px;border-radius:6px;font-size:12px;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#4b5574;">Batal</button>
                        <button type="submit" style="padding:6px 14px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;">Simpan</button>
                    </div>
                </form>
            </div>

            {{-- Daftar mata pelajaran --}}
            @if($tutor && $tutor->tutorSubjects->count())
                @foreach($tutor->tutorSubjects as $ts)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                        <div style="width:36px;height:36px;border-radius:8px;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
                            <i class="bi bi-book"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $ts->subjectCategory->name ?? '-' }}</div>
                            <div style="display:flex;flex-wrap:wrap;gap:3px;margin-top:3px;">
                                @foreach(explode(',', $ts->tingkatan) as $t)
                                    <span style="font-size:10px;background:#f0f3ff;color:#3730a3;padding:2px 8px;border-radius:10px;">{{ trim($t) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div style="display:flex;gap:4px;flex-shrink:0;">
                            <button type="button" onclick="document.getElementById('editSubject{{ $ts->id }}').style.display = document.getElementById('editSubject{{ $ts->id }}').style.display === 'none' ? 'block' : 'none'"
                                    style="width:28px;height:28px;border-radius:6px;border:1px solid #e8eaf0;background:#fff;color:#4b5574;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Edit">
                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                            </button>
                            <form method="POST" action="{{ route('tutor.profil.subject.destroy', $ts) }}" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width:28px;height:28px;border-radius:6px;border:1px solid #fee2e2;background:#fff;color:#ef4444;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Hapus">
                                    <i class="bi bi-trash" style="font-size:12px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Form edit tingkatan --}}
                    <div id="editSubject{{ $ts->id }}" style="display:none;background:#f8fafc;border:1px solid #e8eaf0;border-radius:10px;padding:14px;margin-bottom:8px;">
                        <form method="POST" action="{{ route('tutor.profil.subject.update', $ts) }}">
                            @csrf
                            @method('PUT')
                            <div style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;">Edit Tingkatan — {{ $ts->subjectCategory->name }}</div>
                            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
                                @foreach(['SD','SMP','SMA','Kuliah'] as $tingkat)
                                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;color:#4b5574;cursor:pointer;">
                                        <input type="checkbox" name="tingkatan[]" value="{{ $tingkat }}" {{ in_array($tingkat, explode(',', $ts->tingkatan)) ? 'checked' : '' }} style="width:14px;height:14px;cursor:pointer;">
                                        {{ $tingkat }}
                                    </label>
                                @endforeach
                            </div>
                            <div style="display:flex;justify-content:flex-end;gap:6px;">
                                <button type="button" onclick="document.getElementById('editSubject{{ $ts->id }}').style.display='none'" style="padding:5px 12px;border-radius:6px;font-size:11px;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#4b5574;">Batal</button>
                                <button type="submit" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;">Simpan</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            @else
                <div style="font-size:13px;color:#8890a8;padding:8px 0;">Belum ada mata pelajaran. Klik "Tambah" untuk menambahkan.</div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════
             PENGALAMAN MENGAJAR
        ═══════════════════════════════════════════════════ --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-briefcase" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Pengalaman Mengajar</span>
                </div>
                <button type="button" onclick="document.getElementById('addExpForm').style.display = document.getElementById('addExpForm').style.display === 'none' ? 'block' : 'none'"
                        style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#1e2d6b;">
                    <i class="bi bi-plus-lg" style="font-size:11px;"></i> Tambah
                </button>
            </div>

            <div id="addExpForm" style="display:none;background:#f8fafc;border:1px solid #e8eaf0;border-radius:10px;padding:16px;margin-bottom:14px;">
                <form method="POST" action="{{ route('tutor.profil.experience.store') }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tahun Pengalaman</label>
                            <input type="number" name="experience_years" min="0" required placeholder="contoh: 3"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Periode</label>
                            <input type="text" name="periode" required placeholder="contoh: 2020-2023"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Jumlah Siswa</label>
                            <input type="number" name="jumlah_siswa" min="0" required placeholder="contoh: 50"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Tempat</label>
                            <input type="text" name="tempat" placeholder="contoh: Bimbel XYZ"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:6px;">
                        <button type="button" onclick="document.getElementById('addExpForm').style.display='none'" style="padding:6px 14px;border-radius:6px;font-size:12px;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#4b5574;">Batal</button>
                        <button type="submit" style="padding:6px 14px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;">Simpan</button>
                    </div>
                </form>
            </div>

            @if($tutor && $tutor->tutorProfiles->count())
                @foreach($tutor->tutorProfiles as $exp)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                        <div style="width:36px;height:36px;border-radius:8px;background:#f0fdf4;color:#15803d;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $exp->tempat ?? 'Mengajar Privat' }}</div>
                            <div style="font-size:12px;color:#8890a8;">{{ $exp->experience_years }} tahun · {{ $exp->jumlah_siswa }} siswa · {{ $exp->periode }}</div>
                        </div>
                        <div style="display:flex;gap:4px;flex-shrink:0;">
                            <button type="button" onclick="document.getElementById('editExp{{ $exp->id }}').style.display = document.getElementById('editExp{{ $exp->id }}').style.display === 'none' ? 'block' : 'none'"
                                    style="width:28px;height:28px;border-radius:6px;border:1px solid #e8eaf0;background:#fff;color:#4b5574;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Edit">
                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                            </button>
                            <form method="POST" action="{{ route('tutor.profil.experience.destroy', $exp) }}" onsubmit="return confirm('Hapus pengalaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width:28px;height:28px;border-radius:6px;border:1px solid #fee2e2;background:#fff;color:#ef4444;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Hapus">
                                    <i class="bi bi-trash" style="font-size:12px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="editExp{{ $exp->id }}" style="display:none;background:#f8fafc;border:1px solid #e8eaf0;border-radius:10px;padding:14px;margin-bottom:8px;">
                        <form method="POST" action="{{ route('tutor.profil.experience.update', $exp) }}">
                            @csrf
                            @method('PUT')
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Tahun Pengalaman</label>
                                    <input type="number" name="experience_years" value="{{ $exp->experience_years }}" min="0" required
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Periode</label>
                                    <input type="text" name="periode" value="{{ $exp->periode }}" required
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Jumlah Siswa</label>
                                    <input type="number" name="jumlah_siswa" value="{{ $exp->jumlah_siswa }}" min="0" required
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Tempat</label>
                                    <input type="text" name="tempat" value="{{ $exp->tempat }}"
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;gap:6px;">
                                <button type="button" onclick="document.getElementById('editExp{{ $exp->id }}').style.display='none'" style="padding:5px 12px;border-radius:6px;font-size:11px;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#4b5574;">Batal</button>
                                <button type="submit" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;">Simpan</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            @else
                <div style="font-size:13px;color:#8890a8;padding:8px 0;">Belum ada pengalaman mengajar.</div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════
             RIWAYAT PENDIDIKAN
        ═══════════════════════════════════════════════════ --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-building" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Riwayat Pendidikan</span>
                </div>
                <button type="button" onclick="document.getElementById('addEduForm').style.display = document.getElementById('addEduForm').style.display === 'none' ? 'block' : 'none'"
                        style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#1e2d6b;">
                    <i class="bi bi-plus-lg" style="font-size:11px;"></i> Tambah
                </button>
            </div>

            <div id="addEduForm" style="display:none;background:#f8fafc;border:1px solid #e8eaf0;border-radius:10px;padding:16px;margin-bottom:14px;">
                <form method="POST" action="{{ route('tutor.profil.education.store') }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Jenjang</label>
                            <select name="jenjang" required style="width:100%;height:36px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                                <option value="">-- Pilih --</option>
                                @foreach(['SD','SMP','SMA','D3','S1','S2','S3'] as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Sekolah/Universitas</label>
                            <input type="text" name="sekolah" required placeholder="contoh: UI"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Jurusan</label>
                            <input type="text" name="jurusan" required placeholder="contoh: Matematika"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Periode</label>
                            <input type="text" name="periode" required placeholder="contoh: 2014-2018"
                                   style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        </div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:6px;">
                        <button type="button" onclick="document.getElementById('addEduForm').style.display='none'" style="padding:6px 14px;border-radius:6px;font-size:12px;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#4b5574;">Batal</button>
                        <button type="submit" style="padding:6px 14px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;">Simpan</button>
                    </div>
                </form>
            </div>

            @if($tutor && $tutor->studiedHistories->count())
                @foreach($tutor->studiedHistories as $edu)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                        <div style="width:36px;height:36px;border-radius:8px;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $edu->sekolah }}</div>
                            <div style="font-size:12px;color:#8890a8;">{{ $edu->jenjang ? $edu->jenjang . ' — ' : '' }}{{ $edu->jurusan }} · {{ $edu->periode }}</div>
                        </div>
                        <div style="display:flex;gap:4px;flex-shrink:0;">
                            <button type="button" onclick="document.getElementById('editEdu{{ $edu->id }}').style.display = document.getElementById('editEdu{{ $edu->id }}').style.display === 'none' ? 'block' : 'none'"
                                    style="width:28px;height:28px;border-radius:6px;border:1px solid #e8eaf0;background:#fff;color:#4b5574;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Edit">
                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                            </button>
                            <form method="POST" action="{{ route('tutor.profil.education.destroy', $edu) }}" onsubmit="return confirm('Hapus riwayat pendidikan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width:28px;height:28px;border-radius:6px;border:1px solid #fee2e2;background:#fff;color:#ef4444;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Hapus">
                                    <i class="bi bi-trash" style="font-size:12px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="editEdu{{ $edu->id }}" style="display:none;background:#f8fafc;border:1px solid #e8eaf0;border-radius:10px;padding:14px;margin-bottom:8px;">
                        <form method="POST" action="{{ route('tutor.profil.education.update', $edu) }}">
                            @csrf
                            @method('PUT')
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Jenjang</label>
                                    <select name="jenjang" required style="width:100%;height:34px;padding:0 8px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                        @foreach(['SD','SMP','SMA','D3','S1','S2','S3'] as $j)
                                            <option value="{{ $j }}" {{ $edu->jenjang === $j ? 'selected' : '' }}>{{ $j }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Sekolah</label>
                                    <input type="text" name="sekolah" value="{{ $edu->sekolah }}" required
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Jurusan</label>
                                    <input type="text" name="jurusan" value="{{ $edu->jurusan }}" required
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                                <div>
                                    <label style="font-size:11px;font-weight:500;color:#4b5574;margin-bottom:3px;display:block;">Periode</label>
                                    <input type="text" name="periode" value="{{ $edu->periode }}" required
                                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:6px;font-size:12px;font-family:inherit;background:#fff;outline:none;">
                                </div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;gap:6px;">
                                <button type="button" onclick="document.getElementById('editEdu{{ $edu->id }}').style.display='none'" style="padding:5px 12px;border-radius:6px;font-size:11px;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#4b5574;">Batal</button>
                                <button type="submit" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;">Simpan</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            @else
                <div style="font-size:13px;color:#8890a8;padding:8px 0;">Belum ada riwayat pendidikan.</div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════
             UBAH PASSWORD
        ═══════════════════════════════════════════════════ --}}
        <form method="POST" action="{{ route('tutor.profil.password') }}">
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
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('current_password') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        @error('current_password')
                            <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Password Baru</label>
                        <input type="password" name="password"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid {{ $errors->has('password') ? '#ef4444' : '#e8eaf0' }};border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                        @error('password')
                            <div style="font-size:11px;color:#ef4444;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:4px;display:block;">Konfirmasi</label>
                        <input type="password" name="password_confirmation"
                               style="width:100%;height:36px;padding:0 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;background:#fff;outline:none;">
                    </div>
                </div>
                <div style="display:flex;justify-content:flex-end;margin-top:14px;">
                    <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;font-family:inherit;background:#fff;color:#1e2d6b;">
                        <i class="bi bi-key"></i> Ubah Password
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

@endsection
