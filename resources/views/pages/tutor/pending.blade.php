@extends('layouts.tutor')

@section('page-title', 'Status Pendaftaran')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">

            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4 p-md-5 text-center">

                    @if($status === 'pending')
                        <div class="mb-4">
                            <div style="width:80px;height:80px;border-radius:50%;background:#fef3c7;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-hourglass-split" style="font-size:2rem;color:#f59e0b;"></i>
                            </div>
                        </div>
                        <h4 class="fw-600 mb-2" style="color:#0f172a;">Menunggu Verifikasi</h4>
                        <p class="text-muted mb-4" style="font-size:.9rem;line-height:1.6;">
                            Pendaftaran Anda sebagai tutor telah berhasil dikirim.<br>
                            Tim admin akan memverifikasi data dan dokumen Anda.<br>
                            Proses ini biasanya membutuhkan waktu 1–2 hari kerja.
                        </p>

                        <div class="text-start mx-auto" style="max-width:360px;">
                            <div class="d-flex gap-3 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <div style="width:12px;height:12px;border-radius:50%;background:#22c55e;"></div>
                                    <div style="width:2px;flex:1;background:#e2e8f0;min-height:24px;"></div>
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:.85rem;color:#0f172a;">Pendaftaran Dikirim</div>
                                    <div class="text-muted" style="font-size:.75rem;">{{ $tutor->created_at->translatedFormat('d F Y, H:i') }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <div style="width:12px;height:12px;border-radius:50%;background:#f59e0b;animation:tk-pulse 2s infinite;"></div>
                                    <div style="width:2px;flex:1;background:#e2e8f0;min-height:24px;"></div>
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:.85rem;color:#0f172a;">Menunggu Review Admin</div>
                                    <div class="text-muted" style="font-size:.75rem;">Sedang diproses...</div>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="d-flex flex-column align-items-center">
                                    <div style="width:12px;height:12px;border-radius:50%;background:#cbd5e1;"></div>
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:.85rem;color:#94a3b8;">Verifikasi Selesai</div>
                                    <div class="text-muted" style="font-size:.75rem;">Menunggu</div>
                                </div>
                            </div>
                        </div>

                    @elseif($status === 'rejected')
                        <div class="mb-4">
                            <div style="width:80px;height:80px;border-radius:50%;background:#fee2e2;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-x-circle" style="font-size:2rem;color:#ef4444;"></i>
                            </div>
                        </div>
                        <h4 class="fw-600 mb-2" style="color:#0f172a;">Pendaftaran Ditolak</h4>
                        <p class="text-muted mb-4" style="font-size:.9rem;line-height:1.6;">
                            Maaf, pendaftaran Anda sebagai tutor belum dapat disetujui.<br>
                            Silakan hubungi admin untuk informasi lebih lanjut.
                        </p>
                        <a href="mailto:hello@tutorku.id" class="btn btn-outline-primary">
                            <i class="bi bi-envelope me-1"></i> Hubungi Admin
                        </a>
                    @endif

                </div>
            </div>

            <div class="card border-0 mt-3" style="border-radius:12px;background:#f8fafc;">
                <div class="card-body p-3 d-flex align-items-start gap-3">
                    <i class="bi bi-info-circle text-primary mt-1"></i>
                    <div style="font-size:.85rem;color:#475569;line-height:1.6;">
                        <strong>Data Akun Anda:</strong><br>
                        {{ $user->email }} · Terdaftar {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes tk-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .4; }
    }
</style>
@endpush
@endsection
