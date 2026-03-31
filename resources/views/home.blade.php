{{--
    home.blade.php
    Route: GET /  →  HomeController@index
    Variables expected from controller:
      - $recentNews : Collection of news articles
          (fields: id, title, description, thumbnail, date)
--}}
@extends('layouts.app')

@section('content')

{{-- ============================================================
     HERO — scoped styles
     ============================================================ --}}
<style>
    .hero {
        position: relative;
        min-height: 520px;
        display: flex;
        align-items: center;
        background:
            linear-gradient(100deg, rgba(0, 32, 96, 0.94) 0%, rgba(0, 53, 128, 0.85) 55%, rgba(0, 53, 128, 0.55) 100%),
            url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    }

    /* Structural vertical rule pattern — depth without color */
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: repeating-linear-gradient(
            90deg,
            rgba(255, 255, 255, 0.025) 0px,
            rgba(255, 255, 255, 0.025) 1px,
            transparent 1px,
            transparent 80px
        );
        pointer-events: none;
    }

    .hero .container {
        position: relative;
        z-index: 1;
        padding-top: 80px;
        padding-bottom: 120px; /* room for stats bar */
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
        color: rgba(255, 255, 255, 0.55);
        margin-bottom: 18px;
    }

    .hero__eyebrow::before {
        content: '';
        display: block;
        width: 24px;
        height: 2px;
        background: rgba(255, 255, 255, 0.35);
    }

    .hero__eyebrow i {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.4);
    }

    .hero__title {
        font-family: var(--font-display);
        font-size: clamp(48px, 7vw, 82px);
        font-weight: 900;
        line-height: 0.96;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        color: var(--white);
        margin-bottom: 24px;
        max-width: 640px;
    }

    .hero__title span {
        display: block;
        color: rgba(255, 255, 255, 0.38);
        font-weight: 500;
        font-size: 0.55em;
        letter-spacing: 0.14em;
        margin-bottom: 8px;
    }

    .hero__lead {
        font-size: 16px;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.75;
        max-width: 480px;
        margin-bottom: 36px;
    }

    .hero__actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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
        transition: background var(--ease), color var(--ease);
    }

    .btn-hero-primary:hover {
        background: var(--navy-tint);
        color: var(--navy-dim);
    }

    .btn-hero-ghost {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        color: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 500;
        padding: 14px 28px;
        text-decoration: none;
        transition: border-color var(--ease), color var(--ease);
    }

    .btn-hero-ghost:hover {
        border-color: rgba(255, 255, 255, 0.6);
        color: var(--white);
    }

    /* Stats bar pinned to bottom of hero */
    .hero-stats {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 20, 60, 0.82);
        border-top: 1px solid rgba(255, 255, 255, 0.07);
    }

    .hero-stats .container {
        display: flex;
    }

    .hero-stats__item {
        flex: 1;
        padding: 20px 16px;
        border-right: 1px solid rgba(255, 255, 255, 0.07);
        text-align: center;
    }

    .hero-stats__item:last-child {
        border-right: none;
    }

    .hero-stats__number {
        font-family: var(--font-display);
        font-size: 30px;
        font-weight: 900;
        letter-spacing: 0.02em;
        color: var(--white);
        line-height: 1;
        margin-bottom: 4px;
    }

    .hero-stats__label {
        font-size: 10.5px;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.4);
    }

    @media (max-width: 640px) {
        .hero .container {
            padding-top: 60px;
            padding-bottom: 160px;
        }
        .hero__title { font-size: 52px; }
        .hero-stats .container { flex-wrap: wrap; }
        .hero-stats__item { flex: 1 1 45%; }
    }
</style>

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section class="hero" aria-label="Beranda JakartaProv-CSIRT">
    <div class="container">
        <div>
            <div class="hero__eyebrow">
                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                Tim Tanggap Insiden Siber Resmi DKI Jakarta
            </div>
            <h1 class="hero__title">
                <span>Pemerintah Provinsi DKI Jakarta</span>
                Lindungi<br>Digital<br>Jakarta.
            </h1>
            <p class="hero__lead">
                JakartaProv-CSIRT menjaga infrastruktur digital dan data kritis Provinsi DKI Jakarta dari ancaman siber — 24 jam, 7 hari seminggu.
            </p>
            <div class="hero__actions">
                <a href="{{ route('incidents.create.step1') }}" class="btn-hero-primary">
                    <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                    Lapor Insiden Sekarang
                </a>
                <a href="{{ url('profile') }}" class="btn-hero-ghost">
                    Tentang Kami <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats bar --}}
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


{{-- ============================================================
     ALERT STRIP — red used ONLY for actual warning content
     Remove or update this via a CMS / admin panel in production
     ============================================================ --}}
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


{{-- ============================================================
     BERITA TERKINI
     ============================================================ --}}
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

        <div class="news-grid">
            @forelse($recentNews as $article)
            <article class="news-card">
                <div class="news-card__img-wrap">
                    <img class="news-card__img"
                         src="{{ $article->thumbnail ?? 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=600&q=80' }}"
                         alt="{{ $article->title }}">
                </div>
                <div class="news-card__body">
                    <div class="news-card__date">
                        {{ $article->date->translatedFormat('d M Y') }}
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
            <div style="grid-column: 1 / -1; padding: 48px; text-align: center; color: var(--mid);">
                Belum ada berita terkini.
            </div>
            @endforelse
        </div>

    </div>
</section>


{{-- ============================================================
     PUBLIKASI & LAYANAN
     ============================================================ --}}
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
                <p class="service-card__desc">
                    Notifikasi kerentanan dan ancaman siber terbaru yang memerlukan tindakan segera dari instansi terkait.
                </p>
                <span class="service-card__link">
                    Lihat Peringatan <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </span>
            </a>
            <a href="{{ url('infographics') }}" class="service-card">
                <i class="bi bi-bar-chart-line service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Infografis Keamanan</div>
                <p class="service-card__desc">
                    Visualisasi data ancaman, statistik insiden, dan panduan keamanan informasi dalam format yang mudah dipahami.
                </p>
                <span class="service-card__link">
                    Lihat Infografis <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </span>
            </a>
            <a href="{{ url('laws') }}" class="service-card">
                <i class="bi bi-journal-bookmark service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Peraturan &amp; Kebijakan</div>
                <p class="service-card__desc">
                    Regulasi, kebijakan, dan standar keamanan siber yang berlaku di lingkungan Pemprov DKI Jakarta.
                </p>
                <span class="service-card__link">
                    Lihat Peraturan <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </span>
            </a>
            <a href="{{ url('guides') }}" class="service-card">
                <i class="bi bi-book service-card__icon" aria-hidden="true"></i>
                <div class="service-card__title">Panduan Teknis</div>
                <p class="service-card__desc">
                    Dokumen teknis, SOP, dan panduan implementasi keamanan untuk instansi pemerintah dan mitra.
                </p>
                <span class="service-card__link">
                    Lihat Panduan <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </span>
            </a>
        </div>

    </div>
</section>


{{-- ============================================================
     CTA — LAPOR INSIDEN
     ============================================================ --}}
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
            <a href="{{ route('incidents.create.step1') }}" class="btn-cta-main">
                <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                Lapor Insiden Sekarang
            </a>
            <a href="{{ route('contact.create') }}" class="btn-cta-ghost">
                <i class="bi bi-telephone" aria-hidden="true"></i>
                Hubungi Tim Kami
            </a>
            <div class="cta-section__note">
                <i class="bi bi-clock" aria-hidden="true"></i>&nbsp; Rata-rata respons &lt; 2 jam
            </div>
        </div>

    </div>
</section>

@endsection