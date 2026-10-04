@props(['text' => 'Memuat...'])

<div id="loading-overlay" class="lo" role="status" aria-live="polite" aria-hidden="true">
    <div class="lo-card">
        <div class="lo-spinner"></div>
        <div class="lo-text" id="loading-overlay-text">{{ $text }}</div>
    </div>
</div>

<style>
.lo {
    --lo-backdrop: rgba(15, 23, 42, .55);
    --lo-card-bg: #fff;
    --lo-accent: #0d6efd;
    --lo-track: #e9ecef;
    --lo-text: #212529;
    position: fixed; inset: 0; z-index: 2000;
    display: flex; align-items: center; justify-content: center;
    background: var(--lo-backdrop);
    backdrop-filter: blur(2px);
    opacity: 0; visibility: hidden;
    transition: opacity .2s ease, visibility .2s ease;
}
.lo.is-active { opacity: 1; visibility: visible; }
.lo-card {
    display: flex; flex-direction: column; align-items: center; gap: 14px;
    min-width: 180px; padding: 24px 32px;
    background: var(--lo-card-bg); border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
    transform: translateY(8px) scale(.97);
    transition: transform .2s ease;
}
.lo.is-active .lo-card { transform: none; }
.lo-spinner {
    width: 44px; height: 44px; border-radius: 50%;
    border: 4px solid var(--lo-track); border-top-color: var(--lo-accent);
    animation: lo-spin .8s linear infinite;
}
.lo-text { color: var(--lo-text); font-size: 14px; font-weight: 500; text-align: center; }
@keyframes lo-spin { to { transform: rotate(360deg); } }
@media (prefers-reduced-motion: reduce) {
    .lo, .lo-card { transition: none; }
    .lo-spinner { animation-duration: 2s; }
}
</style>

<script>
window.LoadingOverlay = (function () {
    const el = document.getElementById('loading-overlay');
    const textEl = document.getElementById('loading-overlay-text');
    const defaultText = textEl.textContent;
    let count = 0, timer = null;

    return {
        show(text) {
            count++;
            textEl.textContent = text || defaultText;
            if (count === 1) {
                timer = setTimeout(() => {
                    el.classList.add('is-active');
                    el.setAttribute('aria-hidden', 'false');
                }, 150);
            }
        },
        hide() {
            count = Math.max(0, count - 1);
            if (count === 0) {
                clearTimeout(timer);
                el.classList.remove('is-active');
                el.setAttribute('aria-hidden', 'true');
            }
        }
    };
})();
</script>