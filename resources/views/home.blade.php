@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   HERO — page-scoped only
   ============================================================ */
.hero {
    position: relative;
    display: flex;
    flex-direction: column; /* FIX: column flow so stats bar never overlaps buttons */
    background:
        linear-gradient(100deg, rgba(0,32,96,0.94) 0%, rgba(0,53,128,0.85) 55%, rgba(0,53,128,0.55) 100%),
        url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
}
.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.025) 0px, rgba(255,255,255,0.025) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
}

/* FIX: hero body is now a normal flow element — no min-height trick needed */
.hero__body {
    position: relative;
    z-index: 1;
    padding: 52px 0 44px;
}

.hero__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.55);
    margin-bottom: 18px;
}
.hero__eyebrow::before {
    content: '';
    display: block;
    width: 24px; height: 2px;
    background: rgba(255,255,255,0.35);
}
.hero__eyebrow i { font-size: 11px; color: rgba(255,255,255,0.4); }

.hero__title {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 800;
    line-height: 1;
    letter-spacing: 0.01em;
    text-transform: uppercase;
    color: var(--white);
    margin-bottom: 12px;
}
.hero__lead {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.72);
    line-height: 1.65;
    max-width: 520px;
    margin-bottom: 24px;
}

/* FIX: plain flex row — no absolute positioning near these buttons */
.hero__actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}
.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    color: var(--navy-dim);
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 14px 28px;
    text-decoration: none;
    border: none;
    transition: background var(--ease), color var(--ease);
}
.btn-hero-primary:hover { background: var(--navy-tint); color: var(--navy-dim); }

.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: rgba(255,255,255,0.7);
    border: 1px solid rgba(255,255,255,0.3);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 500;
    padding: 13px 28px;
    text-decoration: none;
    transition: border-color var(--ease), color var(--ease);
}
.btn-hero-ghost:hover { border-color: rgba(255,255,255,0.7); color: var(--white); }

/* Stats bar — FIX: in normal document flow, never overlaps anything */
.hero-stats {
    position: relative;
    z-index: 1;
    background: rgba(0,20,60,0.88);
    border-top: 1px solid rgba(255,255,255,0.07);
}
.hero-stats .container { display: flex; }
.hero-stats__item {
    flex: 1;
    padding: 14px 16px;
    border-right: 1px solid rgba(255,255,255,0.07);
    text-align: center;
}
.hero-stats__item:last-child { border-right: none; }
.hero-stats__number {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 800;
    letter-spacing: 0.02em;
    color: var(--white);
    line-height: 1;
    margin-bottom: 3px;
}
.hero-stats__label {
    font-size: 10.5px;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
}

@media (max-width: 640px) {
    .hero__body { padding: 40px 0 32px; }
    .hero__title { font-size: 36px; }
    .hero__lead { font-size: 14px; }
    .hero-stats .container { flex-wrap: wrap; }
    .hero-stats__item { flex: 1 1 45%; }
    .hero__actions { flex-direction: column; align-items: flex-start; }
    .btn-hero-primary, .btn-hero-ghost { width: 100%; justify-content: center; }
}

/* ============================================================
   NEWS CAROUSEL — horizontal scroll with snap
   ============================================================ */
.news-carousel {
    position: relative;
}

.news-carousel__track {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    border: 1px solid var(--border);
}

.news-carousel__track::-webkit-scrollbar {
    display: none;
}

.news-carousel__card {
    flex: 0 0 33.333333%;
    scroll-snap-align: start;
    border-right: 1px solid var(--border);
    background: var(--white);
    transition: background var(--ease);
}

.news-carousel__card:last-child {
    border-right: none;
}

.news-carousel__card:hover {
    background: var(--mist);
}

.news-carousel__card:hover .news-card__title {
    color: var(--navy);
}

.news-carousel__card:hover .news-card__img {
    transform: scale(1.03);
    filter: grayscale(0%);
}

.news-carousel__btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    width: 40px;
    height: 40px;
    background: var(--white);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background var(--ease), border-color var(--ease), color var(--ease);
    color: var(--ink);
    font-size: 16px;
}

.news-carousel__btn:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}

.news-carousel__btn--prev {
    left: -20px;
}

.news-carousel__btn--next {
    right: -20px;
}

.news-carousel__btn[disabled] {
    opacity: 0.35;
    pointer-events: none;
}

@media (max-width: 960px) {
    .news-carousel__card {
        flex: 0 0 100%;
    }
    .news-carousel__btn {
        display: none;
    }
}

@media (max-width: 420px) {
    .news-carousel__card {
        flex: 0 0 300px;
    }
}

/* ============================================================
   EVENTS SECTION
   ============================================================ */
.events-section {
    padding: 72px 0;
    background: var(--mist);
}

.events-section .events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    margin-bottom: 0;
}

.events-section .event-card {
    background: var(--white);
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    transition: background var(--ease);
    position: relative;
    overflow: hidden;
}

.events-section .event-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--navy);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.2s ease;
}

.events-section .event-card:hover {
    background: var(--navy-tint);
}

.events-section .event-card:hover::after {
    transform: scaleX(1);
}

.events-section .event-card__thumb-wrap {
    overflow: hidden;
}

.events-section .event-card__thumb {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    filter: grayscale(15%);
    transition: filter 0.3s ease, transform 0.3s ease;
}

.events-section .event-card:hover .event-card__thumb {
    filter: grayscale(0%);
    transform: scale(1.03);
}

.events-section .event-card__date-badge {
    position: absolute;
    top: 168px;
    left: 0;
    background: var(--navy);
    color: var(--white);
    padding: 6px 14px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 2;
}

.events-section .event-card__date-badge i {
    font-size: 11px;
    opacity: 0.7;
}

.events-section .event-card__body {
    padding: 44px 20px 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.events-section .event-card__title {
    font-family: var(--font-display);
    font-size: 17px;
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.25;
    margin-bottom: 12px;
    transition: color var(--ease);
}

.events-section .event-card:hover .event-card__title {
    color: var(--navy);
}

.events-section .event-card__meta {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: auto;
}

.events-section .event-card__meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--mid);
}

.events-section .event-card__meta-item i {
    font-size: 11px;
    color: var(--navy);
    flex-shrink: 0;
    width: 13px;
}

.events-section .event-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 14px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--navy);
}

.events-section .event-card__cta i {
    font-size: 10px;
}

@media (max-width: 960px) {
    .events-section .events-grid {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- ================================================================
     HERO
     ================================================================ --}}
<section class="hero" aria-label="Beranda JakartaProv-CSIRT">

    <div class="hero__body">
        <div class="container">
            <div class="hero__eyebrow">
                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                Tim Tanggap Insiden Siber Resmi DKI Jakarta
            </div>
            <h1 class="hero__title">JakartaProvCSIRT</h1>
            <p class="hero__lead">
                Pemerintah Provinsi DKI Jakarta — Computer Security Incident Response Team. Menjaga infrastruktur digital dan data kritis Jakarta dari ancaman siber, 24 jam sehari, 7 hari seminggu.
            </p>
            <div class="hero__actions">
                <a href="{{ route('bug-hunter.dashboard') }}" class="btn-hero-primary">
                    <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                    Lapor Insiden Sekarang
                </a>
                <a href="{{ url('profile') }}" class="btn-hero-ghost">
                    Tentang Kami <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="hero-stats" aria-label="Statistik JakartaProv-CSIRT">
        <div class="container">
            <div class="hero-stats__item">
                <div class="hero-stats__number">157</div>
                <div class="hero-stats__label">Insiden Ditangani</div>
            </div>
            <div class="hero-stats__item">
                <div class="hero-stats__number">24/7</div>
                <div class="hero-stats__label">Respons Siap</div>
            </div>
            <div class="hero-stats__item">
                <div class="hero-stats__number">89K+</div>
                <div class="hero-stats__label">Pengunjung</div>
            </div>
            <div class="hero-stats__item">
                <div class="hero-stats__number">&lt;2J</div>
                <div class="hero-stats__label">Rata-rata Respons</div>
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     ALERT STRIP — red for warning content only
     ================================================================ --}}
<div class="alert-strip" role="alert" aria-live="polite">
    <div class="container">
        <div class="alert-strip__icon" aria-hidden="true">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <div class="alert-strip__label">Peringatan Aktif</div>
            <div class="alert-strip__text">
                Kerentanan kritis pada Apache HTTP Server (CVE-2024-38476) — segera perbarui ke versi 2.4.62 atau lebih tinggi.
            </div>
        </div>
        <a href="{{ route('warnings.index') }}" class="alert-strip__cta">
            Lihat Detail <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</div>


{{-- ================================================================
     BERITA TERKINI — slide-able carousel
     ================================================================ --}}
<section class="news-section" aria-labelledby="news-heading">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title" id="news-heading">Berita Terkini</h2>
                <p class="section-subtitle">Informasi terbaru dari JakartaProv-CSIRT</p>
            </div>
            <a href="{{ route('news.index') }}" class="section-link">
                Lihat Semua <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="news-carousel">
            <button class="news-carousel__btn news-carousel__btn--prev" aria-label="Sebelumnya">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
            </button>

            <div class="news-carousel__track">
                @forelse($recentNews as $article)
                <article class="news-carousel__card">
                    <div class="news-card__img-wrap">
                        <img class="news-card__img"
                             src="{{ $article->thumbnail }}"
                             alt="{{ $article->title }}"
                             onerror="this.src='https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=600&q=80'">
                    </div>
                    <div class="news-card__body">
                        <div class="news-card__date">
                            {{ $article->date->format('d M Y') }}
                        </div>
                        <h3 class="news-card__title">
                            <a href="{{ route('news.show', $article->id) }}">
                                {{ $article->title }}
                            </a>
                        </h3>
                        <p class="news-card__excerpt">
                            {{ Str::limit($article->description, 130) }}
                        </p>
                        <span class="news-card__more">
                            Baca Selengkapnya <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </span>
                    </div>
                </article>
                @empty
                <div style="flex:0 0 100%; padding:64px 24px; text-align:center;">
                    <i class="bi bi-newspaper" style="font-size:40px;color:var(--border);display:block;margin-bottom:12px;"></i>
                    <p style="color:var(--mid);font-size:14px;">
                        Belum ada berita. Jalankan: <code>php artisan db:seed --class=CybersecurityNewsSeeder</code>
                    </p>
                </div>
                @endforelse
            </div>

            <button class="news-carousel__btn news-carousel__btn--next" aria-label="Selanjutnya">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</section>


{{-- ================================================================
     PUBLIKASI & LAYANAN
     ================================================================ --}}
<section class="services-section" aria-labelledby="services-heading">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title" id="services-heading">Publikasi &amp; Layanan</h2>
                <p class="section-subtitle">Sumber daya keamanan siber untuk Pemprov DKI Jakarta</p>
            </div>
        </div>
        <div class="services-grid">
            <a href="{{ url('warnings') }}" class="service-card">
                <i class="bi bi-shield-exclamation service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Peringatan Keamanan</div>
                <p class="service-card__desc">Notifikasi kerentanan dan ancaman siber terbaru yang memerlukan tindakan segera.</p>
                <span class="service-card__link">Lihat Peringatan <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ url('infographics') }}" class="service-card">
                <i class="bi bi-bar-chart-line service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Infografis Keamanan</div>
                <p class="service-card__desc">Visualisasi data ancaman, statistik insiden, dan panduan keamanan informasi.</p>
                <span class="service-card__link">Lihat Infografis <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ url('laws') }}" class="service-card">
                <i class="bi bi-journal-bookmark service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Peraturan &amp; Kebijakan</div>
                <p class="service-card__desc">Regulasi, kebijakan, dan standar keamanan siber yang berlaku di DKI Jakarta.</p>
                <span class="service-card__link">Lihat Peraturan <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ url('guides') }}" class="service-card">
                <i class="bi bi-book service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Panduan Teknis</div>
                <p class="service-card__desc">Dokumen teknis, SOP, dan panduan implementasi keamanan untuk instansi pemerintah.</p>
                <span class="service-card__link">Lihat Panduan <i class="bi bi-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>


{{-- ================================================================
     ACARA MENDATANG
     ================================================================ --}}
<section class="events-section" aria-labelledby="events-heading">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title" id="events-heading">Acara Mendatang</h2>
                <p class="section-subtitle">Webinar, workshop, dan kegiatan keamanan siber</p>
            </div>
            <a href="{{ route('events.index') }}" class="section-link">
                Lihat Semua <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        @if($upcomingEvents->isNotEmpty())
        <div class="events-grid">
            @foreach($upcomingEvents as $event)
            <a href="{{ route('events.show', $event) }}" class="event-card" aria-label="{{ $event->title }}">
                <div class="event-card__thumb-wrap">
                    <img class="event-card__thumb"
                         src="{{ $event->thumbnail ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80' }}"
                         alt="{{ $event->title }}"
                         onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'">
                </div>
                @if($event->event_date)
                <div class="event-card__date-badge">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    {{ $event->event_date->format('d M Y') }}
                </div>
                @endif
                <div class="event-card__body">
                    <h3 class="event-card__title">{{ $event->title }}</h3>
                    <div class="event-card__meta">
                        @if($event->location)
                        <div class="event-card__meta-item">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        @endif
                        @if($event->event_type)
                        <div class="event-card__meta-item">
                            <i class="bi bi-tag-fill" aria-hidden="true"></i>
                            <span>{{ ucfirst($event->event_type) }}</span>
                        </div>
                        @endif
                    </div>
                    <span class="event-card__cta">
                        Detail Event <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div style="padding:64px 24px; text-align:center; border:1px solid var(--border); background:var(--white);">
            <i class="bi bi-calendar-x" style="font-size:40px;color:var(--border);display:block;margin-bottom:12px;"></i>
            <p style="color:var(--mid);font-size:14px;">Belum ada acara mendatang.</p>
        </div>
        @endif
    </div>
</section>


{{-- ================================================================
     CTA
     ================================================================ --}}
<section class="cta-section" aria-labelledby="cta-heading">
    <div class="container">
        <div class="cta-section__content">
            <div class="cta-section__eyebrow">Respons Cepat 24/7</div>
            <h2 class="cta-section__title" id="cta-heading">
                Temukan<br>Insiden Siber<br>di Sistem Anda?
            </h2>
            <p class="cta-section__desc">
                Tim JakartaProv-CSIRT siap merespons dan membantu penanganan insiden keamanan siber di lingkungan Pemprov DKI Jakarta kapan saja.
            </p>
        </div>
        <div class="cta-section__actions">
            <a href="{{ route('bug-hunter.dashboard') }}" class="btn-cta-main">
                <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                Lapor Insiden Sekarang
            </a>
            <a href="{{ route('contact.create') }}" class="btn-cta-ghost">
                <i class="bi bi-telephone" aria-hidden="true"></i> Hubungi Tim Kami
            </a>
            <div class="cta-section__note">
                <i class="bi bi-clock" aria-hidden="true"></i>&nbsp; Rata-rata respons &lt; 2 jam
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var track = document.querySelector('.news-carousel__track');
    if (!track) return;

    var container = track.closest('.news-carousel');
    var prev = container.querySelector('.news-carousel__btn--prev');
    var next = container.querySelector('.news-carousel__btn--next');

    function updateButtons() {
        if (!prev || !next) return;
        var atStart = track.scrollLeft < 4;
        var atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth;
        if (prev.disabled !== atStart) prev.disabled = atStart;
        if (next.disabled !== atEnd) next.disabled = atEnd;
    }

    function scrollBy(dir) {
        var card = track.querySelector('.news-carousel__card');
        if (!card) return;
        var amount = card.offsetWidth + 1;
        track.scrollBy({ left: dir * amount, behavior: 'smooth' });
    }

    if (prev) prev.addEventListener('click', function () { scrollBy(-1); });
    if (next) next.addEventListener('click', function () { scrollBy(1); });

    track.addEventListener('scroll', updateButtons);
    updateButtons();

    // Auto-slide hint
    var userInteracted = false;
    var autoTimer;

    function stopAuto() { userInteracted = true; clearInterval(autoTimer); }

    setTimeout(function () {
        if (userInteracted) return;
        track.scrollBy({ left: 140, behavior: 'smooth' });
        setTimeout(function () {
            if (!userInteracted) track.scrollBy({ left: -140, behavior: 'smooth' });
        }, 1200);
    }, 2000);

    autoTimer = setInterval(function () {
        if (userInteracted) return;
        var card = track.querySelector('.news-carousel__card');
        if (!card) return;
        if (next && !next.disabled) {
            track.scrollBy({ left: card.offsetWidth + 1, behavior: 'smooth' });
        } else {
            track.scrollTo({ left: 0, behavior: 'smooth' });
        }
    }, 5000);

    if (prev) prev.addEventListener('click', stopAuto);
    if (next) next.addEventListener('click', stopAuto);
    track.addEventListener('touchstart', stopAuto, { once: true });
});
</script>

@endsection