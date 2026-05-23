{{--
    Component: tutor-card
    Props: $tutor (object)
--}}
<div class="col-sm-6 col-lg-4">
    <div class="tk-tutor-card">

        {{-- Header: avatar + nama + rating --}}
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="tk-tutor-avatar">
                {{ strtoupper(substr($tutor->user->name ?? 'TK', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="tk-tutor-name text-truncate">
                    {{ $tutor->user->name ?? 'Nama Tutor' }}
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="tk-stars">
                        @php $rating = $tutor->avg_rating ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($rating))
                                <i class="bi bi-star-fill"></i>
                            @elseif($i - $rating < 1)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="text-muted ms-1" style="font-size:.75rem;">
                        {{ number_format($rating, 1) }}
                        ({{ $tutor->review_count ?? 0 }})
                    </span>
                </div>
            </div>
        </div>

        {{-- Mata pelajaran badges --}}
        <div class="mb-2">
            @forelse($tutor->mataPelajaran->take(3) as $mp)
                <span class="tk-badge-subject">{{ $mp->nama }}</span>
            @empty
                <span class="tk-badge-subject text-muted">Umum</span>
            @endforelse
        </div>

        {{-- Bio singkat --}}
        <p class="text-muted mb-3"
           style="font-size:.8125rem;line-height:1.5;
                  display:-webkit-box;-webkit-line-clamp:2;
                  -webkit-box-orient:vertical;overflow:hidden;">
            {{ $tutor->bio ?? 'Tutor berpengalaman siap membantu belajarmu.' }}
        </p>

        {{-- Pengalaman + sesi --}}
        <div class="d-flex gap-3 mb-3">
            <div class="d-flex align-items-center gap-1">
                <i class="bi bi-briefcase text-muted" style="font-size:.8rem;"></i>
                <span style="font-size:.75rem;color:var(--tk-text-muted);">
                    {{ $tutor->experience_years ?? 0 }} thn pengalaman
                </span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <i class="bi bi-person-check text-muted" style="font-size:.8rem;"></i>
                <span style="font-size:.75rem;color:var(--tk-text-muted);">
                    {{ $tutor->session_count ?? 0 }} sesi
                </span>
            </div>
        </div>

        <div class="tk-divider"></div>

        {{-- Harga + CTA --}}
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="tk-tutor-price">
                    Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}
                </span>
                <span class="tk-tutor-price-label">/jam</span>
            </div>
            <a href="{{ route('tutor.show', $tutor->id) }}"
               class="tk-btn-outline-primary">
                <i class="bi bi-calendar3"></i> Lihat Jadwal
            </a>
        </div>

    </div>
</div>
