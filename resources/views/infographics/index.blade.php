{{--
    infographics/index.blade.php
    Controller: InfographicController@index
    Variable:   $infographics  (paginated collection — fields: id, title, thumbnail)

    Lightbox is pure vanilla JS — no Fancybox, no external library required.
    Clicking any card opens the image fullscreen with title + close button.
--}}
@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.infographics-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.infographics-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
}
.infographics-header .container { position: relative; z-index: 1; }

.infographics-header__eyebrow {
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.infographics-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.infographics-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.infographics-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
}

/* ============================================================
   BODY
   ============================================================ */
.infographics-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

/* ============================================================
   GALLERY GRID
   3 columns — matches the real CSIRT site layout
   ============================================================ */
.infographics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    margin-bottom: 40px;
}

/* Each card is a button so it's keyboard accessible */
.infographic-card {
    background: var(--white);
    display: flex;
    flex-direction: column;
    cursor: pointer;
    border: none;
    padding: 0;
    text-align: left;
    position: relative;
    overflow: hidden;
    transition: background var(--ease);
}
.infographic-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--navy);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.2s ease;
}
.infographic-card:hover { background: var(--navy-tint); }
.infographic-card:hover::after { transform: scaleX(1); }
.infographic-card:focus-visible {
    outline: 3px solid var(--navy);
    outline-offset: -3px;
}

/* Thumbnail */
.infographic-card__img-wrap {
    overflow: hidden;
    position: relative;
}
.infographic-card__img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    filter: grayscale(10%);
    transition: transform 0.3s ease, filter 0.3s ease;
}
.infographic-card:hover .infographic-card__img {
    transform: scale(1.04);
    filter: grayscale(0%);
}

/* Zoom hint overlay — appears on hover */
.infographic-card__overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,32,96,0);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.25s ease;
}
.infographic-card:hover .infographic-card__overlay {
    background: rgba(0,32,96,0.35);
}
.infographic-card__zoom {
    width: 44px; height: 44px;
    background: var(--white);
    color: var(--navy);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.infographic-card:hover .infographic-card__zoom {
    opacity: 1;
    transform: scale(1);
}

/* Card footer */
.infographic-card__body {
    padding: 16px 18px 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.infographic-card__title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.3;
    margin-bottom: 8px;
    transition: color var(--ease);
}
.infographic-card:hover .infographic-card__title { color: var(--navy); }

.infographic-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-family: var(--font-display);
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--navy);
    pointer-events: none; /* card itself is the click target */
}
.infographic-card__cta i { font-size: 10px; }

/* Empty state */
.infographics-empty {
    grid-column: 1 / -1;
    padding: 80px 24px;
    text-align: center;
    background: var(--white);
}
.infographics-empty i {
    font-size: 44px;
    color: var(--border);
    display: block;
    margin-bottom: 14px;
}
.infographics-empty p { font-size: 15px; color: var(--mid); }

/* Pagination */
nav[role="navigation"] ul {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    justify-content: center;
}
nav[role="navigation"] li a,
nav[role="navigation"] li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    font-size: 13px;
    font-weight: 500;
    color: var(--ink);
    border: 1px solid var(--border);
    background: var(--white);
    text-decoration: none;
    transition: background var(--ease), border-color var(--ease), color var(--ease);
}
nav[role="navigation"] li a:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}
nav[role="navigation"] li span[aria-current="page"] {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--white);
    font-weight: 700;
}
nav[role="navigation"] li span.cursor-default {
    color: var(--mid);
    background: var(--mist);
}
.pagination-wrap {
    display: flex;
    justify-content: center;
    margin-top: 8px;
}

/* ============================================================
   LIGHTBOX
   Vanilla JS — no external library
   ============================================================ */
.lightbox-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(10,15,26,0.96);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
}
.lightbox-backdrop.is-open {
    opacity: 1;
    pointer-events: all;
}

.lightbox-inner {
    position: relative;
    max-width: 900px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    transform: translateY(12px);
    transition: transform 0.2s ease;
}
.lightbox-backdrop.is-open .lightbox-inner {
    transform: translateY(0);
}

.lightbox-close {
    position: absolute;
    top: -48px;
    right: 0;
    width: 40px; height: 40px;
    background: transparent;
    border: 1px solid rgba(255,255,255,0.25);
    color: var(--white);
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background var(--ease), border-color var(--ease);
}
.lightbox-close:hover {
    background: var(--navy);
    border-color: var(--navy);
}

.lightbox-img {
    width: 100%;
    max-height: 80vh;
    object-fit: contain;
    display: block;
    border: 2px solid rgba(255,255,255,0.06);
}

.lightbox-caption {
    width: 100%;
    background: var(--navy-dim);
    padding: 14px 20px;
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.02em;
    color: var(--white);
    text-transform: uppercase;
}

/* Nav arrows */
.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px; height: 44px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: var(--white);
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background var(--ease);
}
.lightbox-nav:hover { background: var(--navy); border-color: var(--navy); }
.lightbox-nav.prev { left: -60px; }
.lightbox-nav.next { right: -60px; }

/* Counter */
.lightbox-counter {
    position: absolute;
    top: -48px;
    left: 0;
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.4);
    letter-spacing: 0.06em;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .infographics-grid { grid-template-columns: 1fr 1fr; }
    .lightbox-nav { display: none; }
}
@media (max-width: 540px) {
    .infographics-grid { grid-template-columns: 1fr; }
}
</style>


{{-- PAGE HEADER --}}
<div class="infographics-header">
    <div class="container">
        <div class="infographics-header__eyebrow">
            <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
            JakartaProv-CSIRT
        </div>
        <h1>Infografis Keamanan</h1>
        <p class="infographics-header__sub">
            Panduan visual tentang ancaman dan praktik keamanan siber dari JakartaProv-CSIRT.
        </p>
    </div>
</div>


{{-- GALLERY BODY --}}
<div class="infographics-body">
    <div class="container">

        <div class="infographics-grid" id="infographics-grid">
            @forelse($infographics as $index => $infographic)
            {{-- Each card is a <button> for accessibility; JS handles the click --}}
            <button class="infographic-card"
                    type="button"
                    data-img="{{ $infographic->thumbnail }}"
                    data-title="{{ $infographic->title }}"
                    data-index="{{ $loop->index }}"
                    aria-label="Lihat infografis: {{ $infographic->title }}">

                <div class="infographic-card__img-wrap">
                    <img class="infographic-card__img"
                         src="{{ $infographic->thumbnail }}"
                         alt="{{ $infographic->title }}"
                         loading="lazy"
                         onerror="this.closest('.infographic-card__img-wrap').style.background='var(--mist)';this.style.display='none'">
                    <div class="infographic-card__overlay" aria-hidden="true">
                        <div class="infographic-card__zoom">
                            <i class="bi bi-zoom-in"></i>
                        </div>
                    </div>
                </div>

                <div class="infographic-card__body">
                    <div class="infographic-card__title">{{ $infographic->title }}</div>
                    <span class="infographic-card__cta">
                        Lihat Gambar <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </button>
            @empty
            <div class="infographics-empty">
                <i class="bi bi-image" aria-hidden="true"></i>
                <p>Belum ada infografis. Tambahkan melalui Admin Dashboard.</p>
            </div>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $infographics->links() }}
        </div>

    </div>
</div>


{{-- LIGHTBOX --}}
<div class="lightbox-backdrop"
     id="lightbox"
     role="dialog"
     aria-modal="true"
     aria-label="Tampilan penuh infografis">

    <div class="lightbox-inner">
        <span class="lightbox-counter" id="lb-counter" aria-live="polite"></span>

        <button class="lightbox-close" id="lb-close" aria-label="Tutup lightbox">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>

        <button class="lightbox-nav prev" id="lb-prev" aria-label="Infografis sebelumnya">
            <i class="bi bi-chevron-left" aria-hidden="true"></i>
        </button>

        <img class="lightbox-img" id="lb-img" src="" alt="">

        <div class="lightbox-caption" id="lb-caption"></div>

        <button class="lightbox-nav next" id="lb-next" aria-label="Infografis berikutnya">
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
</div>


{{-- VANILLA JS LIGHTBOX — no framework --}}
<script>
(function () {
    // Build index from all cards on this paginated page
    const cards = Array.from(document.querySelectorAll('.infographic-card[data-img]'));
    const lb      = document.getElementById('lightbox');
    const lbImg   = document.getElementById('lb-img');
    const lbCap   = document.getElementById('lb-caption');
    const lbClose = document.getElementById('lb-close');
    const lbPrev  = document.getElementById('lb-prev');
    const lbNext  = document.getElementById('lb-next');
    const lbCount = document.getElementById('lb-counter');

    let current = 0;

    function open(idx) {
        current = idx;
        const card = cards[current];
        lbImg.src = card.dataset.img;
        lbImg.alt = card.dataset.title;
        lbCap.textContent = card.dataset.title;
        lbCount.textContent = (current + 1) + ' / ' + cards.length;
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        lbClose.focus();

        // Hide nav arrows if only one image
        lbPrev.style.display = cards.length < 2 ? 'none' : '';
        lbNext.style.display = cards.length < 2 ? 'none' : '';
    }

    function close() {
        lb.classList.remove('is-open');
        document.body.style.overflow = '';
        // Return focus to the card that opened it
        cards[current].focus();
    }

    function prev() { open((current - 1 + cards.length) % cards.length); }
    function next() { open((current + 1) % cards.length); }

    // Open on card click
    cards.forEach((card, idx) => {
        card.addEventListener('click', () => open(idx));
    });

    // Close button
    lbClose.addEventListener('click', close);

    // Nav buttons
    lbPrev.addEventListener('click', (e) => { e.stopPropagation(); prev(); });
    lbNext.addEventListener('click', (e) => { e.stopPropagation(); next(); });

    // Click backdrop to close
    lb.addEventListener('click', (e) => {
        if (e.target === lb) close();
    });

    // Keyboard: Escape = close, Arrow keys = navigate
    document.addEventListener('keydown', (e) => {
        if (!lb.classList.contains('is-open')) return;
        if (e.key === 'Escape')      close();
        if (e.key === 'ArrowLeft')   prev();
        if (e.key === 'ArrowRight')  next();
    });
})();
</script>

@endsection