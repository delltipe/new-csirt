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
        linear-gradient(100deg, rgba(0,32,96,0.82) 0%, rgba(0,53,128,0.68) 52%, rgba(0,53,128,0.32) 100%),
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

/* B — scrim plate behind text only: guarantees contrast on any image (see /demo/hero-contrast) */
.hero__scrim{
    display: inline-block;
    max-width: 640px;
    background: linear-gradient(90deg, rgba(10,15,26,0.72) 0%, rgba(10,15,26,0.58) 68%, rgba(10,15,26,0.00) 100%);
    padding: 18px 22px 16px;
    margin: -18px -22px -16px;
    border-left: 3px solid var(--navy);
}
.hero__scrim .hero__title,
.hero__scrim .hero__lead{ text-shadow: 0 1px 10px rgba(0,0,0,0.35); }
@media (max-width: 640px){
    .hero__scrim{ display:block; max-width:none; margin: -14px -16px; padding: 14px 16px; }
}

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
   HERO SLIDER — slidable, admin-updatable
   ============================================================ */
.hero--slider{
    overflow: hidden;
    background: var(--ink);
}
.hero--slider .hero__track{
    display: flex;
    transition: transform 0.45s ease;
    will-change: transform;
}
.hero__slide{
    flex: 0 0 100%;
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 340px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.hero__slide .hero__title{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hero__slide .hero__lead{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hero__slide::before{
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.025) 0px, rgba(255,255,255,0.025) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
    z-index: 0;
}
.hero__slide .hero__body{
    flex: 1;
    display: flex;
    align-items: center;
}
.hero__nav{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
    width: 44px;
    height: 44px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background var(--ease), border-color var(--ease), color var(--ease);
    backdrop-filter: blur(4px);
}
.hero__nav:hover{
    background: rgba(255,255,255,0.22);
    border-color: rgba(255,255,255,0.45);
    color: var(--white);
}
.hero__nav--prev{ left: 16px; }
.hero__nav--next{ right: 16px; }
.hero__nav i{ font-size: 18px; }
.hero__dots{
    position: absolute;
    bottom: 18px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 3;
}
.hero__dot{
    width: 10px;
    height: 10px;
    border: 1px solid rgba(255,255,255,0.55);
    background: transparent;
    cursor: pointer;
    transition: background var(--ease), border-color var(--ease);
    padding: 0;
}
.hero__dot.is-active{
    background: var(--white);
    border-color: var(--white);
}
@media (max-width: 640px){
    .hero__nav{ display: none; }
    .hero__slide{ min-height: 320px; }
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
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
}
.news-carousel__card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: var(--navy);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.2s ease;
}
.news-carousel__card:last-child {
    border-right: none;
}
.news-carousel__card:hover {
    background: var(--navy-tint);
}
.news-carousel__card:hover::after {
    transform: scaleX(1);
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
    gap: 16px;
    background: transparent;
    border: none;
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
    border: 1px solid var(--border);
}

.events-section .event-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
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
    padding: 22px 20px 22px;
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

/* ============================================================
   CONTENT WRAPPER — Halftone Edge Glow (slate, wider, Both L+R)
   Replaces mesh: two halftone fields with glow, desaturated so it
   doesn't clash with var(--navy) interactive buttons. Brighten on
   hover (proximity), edge -110px, wide 88%×68% mask.
   ============================================================ */
.content-bg-wrap{ position:relative; overflow:hidden; isolation:isolate; }
.content-bg-wrap.wash{
    background: linear-gradient(180deg, var(--white) 0%, var(--white) 32%, var(--mist) 92%);
}
.content-bg-wrap .news-section,
.content-bg-wrap .services-section,
.content-bg-wrap .events-section{
    background: transparent !important;
    position: relative; z-index:1;
}
.content-bg-wrap .alert-strip{ position:relative; z-index:1; }
.halftone-field{ position:absolute; width:560px; height:760px; pointer-events:none; z-index:0; overflow:hidden; --ht-dot:148,164,188; --ht-glow:148,164,188; transition: opacity 0.18s ease, transform 0.12s ease; will-change: transform, opacity; }
.halftone-field.left{ left:-70px; top:4%; }
.halftone-field.right{ right:-70px; top:30%; }
.halftone-glow{ position:absolute; inset:-14%; background: radial-gradient(ellipse 72% 62% at 50% 50%, rgba(var(--ht-glow),0.24) 0%, rgba(var(--ht-glow),0.13) 28%, rgba(var(--ht-glow),0.06) 48%, transparent 70%); filter: blur(12px); }
.halftone-dots{ position:absolute; inset:0; background-image: radial-gradient(circle, rgba(var(--ht-dot),0.92) 1.40px, transparent 1.80px); background-size:13px 13px; opacity:0.96; -webkit-mask-image: radial-gradient(ellipse 92% 70% at 50% 50%, black 58%, transparent 88%); mask-image: radial-gradient(ellipse 92% 70% at 50% 50%, black 58%, transparent 88%); }
/* Moved 40px toward center + softer top/bottom: shorter ellipse so vertical fades well before field edge — no straight top/bottom line */
.halftone-field.left .halftone-dots{ -webkit-mask-image: radial-gradient(ellipse 108% 78% at 22% 50%, black 18%, transparent 72%); mask-image: radial-gradient(ellipse 108% 78% at 22% 50%, black 18%, transparent 72%); }
.halftone-field.right .halftone-dots{ -webkit-mask-image: radial-gradient(ellipse 108% 78% at 78% 50%, black 18%, transparent 72%); mask-image: radial-gradient(ellipse 108% 78% at 78% 50%, black 18%, transparent 72%); }
.halftone-field.left .halftone-glow{ background: radial-gradient(ellipse 74% 62% at 28% 50%, rgba(var(--ht-glow),0.19) 0%, rgba(var(--ht-glow),0.11) 32%, rgba(var(--ht-glow),0.04) 56%, transparent 76%); }
.halftone-field.right .halftone-glow{ background: radial-gradient(ellipse 74% 62% at 72% 50%, rgba(var(--ht-glow),0.19) 0%, rgba(var(--ht-glow),0.11) 32%, rgba(var(--ht-glow),0.04) 56%, transparent 76%); }
@media (max-width: 1200px){ .halftone-field{ width:420px; height:520px; } }
@media (max-width: 960px){
    .events-section .events-grid { grid-template-columns: 1fr; }
    .halftone-field{ opacity:0.55; }
}
html.accessibility-pause-animations .halftone-field{ animation-play-state: paused !important; }
</style>

{{-- ================================================================
     HERO SLIDER — admin-updatable, slidable
     ================================================================ --}}
@php
    $heroSlides = $slides ?? collect();
    $hasSlider = $heroSlides->isNotEmpty();
@endphp
<section class="hero {{ $hasSlider ? 'hero--slider' : '' }}" aria-label="Beranda JakartaProv-CSIRT" id="heroSlider">
    @if($hasSlider)
        <div class="hero__track" id="heroTrack">
            @foreach($heroSlides as $index => $slide)
                @php
                    $img = $slide->gambar;
                    if ($img && !str_starts_with($img, 'http://') && !str_starts_with($img, 'https://')) {
                        $img = \Illuminate\Support\Facades\Storage::url($img);
                    }
                    if (!$img) {
                        $img = 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80';
                    }
                    $link = $slide->tautan ?: route('bug-hunter.dashboard');
                @endphp
                <div class="hero__slide {{ $index === 0 ? 'is-active' : '' }}" data-index="{{ $index }}"
                     style="background: linear-gradient(100deg, rgba(0,32,96,0.82) 0%, rgba(0,53,128,0.68) 52%, rgba(0,53,128,0.32) 100%), url('{{ $img }}') center/cover no-repeat;">
                    <div class="hero__body">
                        <div class="container">
                            <div class="hero__scrim">
                                <div class="hero__eyebrow">
                                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                                    Tim Tanggap Insiden Siber Resmi DKI Jakarta
                                </div>
                                <h1 class="hero__title">{{ $slide->judul }}</h1>
                                @if($slide->subjudul)
                                    <p class="hero__lead">{{ $slide->subjudul }}</p>
                                @endif
                                <div class="hero__actions">
                                    <a href="{{ $link }}" class="btn-hero-primary">
                                        <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                                        {{ $slide->teks_tautan ?: 'LAPOR INSIDEN SEKARANG' }}
                                    </a>
                                    <a href="{{ url('profile') }}" class="btn-hero-ghost">
                                        Tentang Kami <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($heroSlides->count() > 1)
            <button class="hero__nav hero__nav--prev" aria-label="Sebelumnya" id="heroPrev">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
            </button>
            <button class="hero__nav hero__nav--next" aria-label="Selanjutnya" id="heroNext">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
            </button>
            <div class="hero__dots" role="tablist" aria-label="Navigasi slide" id="heroDots">
                @foreach($heroSlides as $index => $slide)
                    <button class="hero__dot {{ $index === 0 ? 'is-active' : '' }}" role="tab" aria-label="Slide {{ $index + 1 }}" data-index="{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
        @endif
    @else
        {{-- Fallback: single static slide when no data --}}
        <div class="hero__body">
            <div class="container">
                <div class="hero__scrim">
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
        </div>
    @endif

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


<div class="content-bg-wrap wash" id="contentWrap">
    <div class="halftone-field left" id="halftoneLeft" aria-hidden="true">
        <div class="halftone-glow"></div>
        <div class="halftone-dots"></div>
    </div>
    <div class="halftone-field right" id="halftoneRight" aria-hidden="true">
        <div class="halftone-glow"></div>
        <div class="halftone-dots"></div>
    </div>

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
                <a href="{{ route('news.show', $article->id) }}" class="news-carousel__card">
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
                            {{ $article->title }}
                        </h3>
                        <p class="news-card__excerpt">
                            {{ Str::limit($article->description, 130) }}
                        </p>
                        <span class="news-card__more">
                            Baca Selengkapnya <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
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
</div>{{-- /content-bg-wrap wash --}}


{{-- ================================================================
     CTA — untouched navy-dim, outside wrapper
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
/* Halftone brighten — slate, wider, Both L+R -110px, present+glow */
document.addEventListener('DOMContentLoaded', function () {
    (function(){
        var wrap=document.getElementById('contentWrap');
        var left=document.getElementById('halftoneLeft');
        var right=document.getElementById('halftoneRight');
        if(!wrap||!left||!right) return;
        if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        function isPaused(){ return document.documentElement.classList.contains('accessibility-pause-animations') || document.hidden; }
        wrap.addEventListener('mousemove', function(e){
            if(isPaused()) return;
            var rL=left.getBoundingClientRect(), rR=right.getBoundingClientRect();
            var dL=Math.hypot(e.clientX-(rL.left+rL.width/2), e.clientY-(rL.top+rL.height/2));
            var dR=Math.hypot(e.clientX-(rR.left+rR.width/2), e.clientY-(rR.top+rR.height/2));
            var tL=Math.max(0, 1 - dL/520), tR=Math.max(0, 1 - dR/520);
            left.style.opacity= 0.62 + tL*0.36;
            right.style.opacity= 0.62 + tR*0.36;
        });
        wrap.addEventListener('mouseleave', function(){ left.style.opacity=''; right.style.opacity=''; });
    })();
});
/* Hero slider — clone news-carousel logic, prefers-reduced-motion + pause-animations pause */
document.addEventListener('DOMContentLoaded', function(){
    var hero = document.getElementById('heroSlider');
    var track = document.getElementById('heroTrack');
    if(!hero || !track) return;
    var slides = track.children;
    var total = slides.length;
    if(total <= 1) return;
    var dots = document.querySelectorAll('#heroDots .hero__dot');
    var prev = document.getElementById('heroPrev');
    var next = document.getElementById('heroNext');
    var idx = 0;
    var timer = null;
    var userInteracted = false;
    function isPaused(){ return document.documentElement.classList.contains('accessibility-pause-animations') || document.hidden || window.matchMedia('(prefers-reduced-motion: reduce)').matches; }
    function go(n){
        idx = (n + total) % total;
        track.style.transform = 'translateX(' + (-idx * 100) + '%)';
        for(var i=0;i<dots.length;i++){ var active=i===idx; dots[i].classList.toggle('is-active', active); dots[i].setAttribute('aria-selected', active ? 'true' : 'false'); }
        for(var j=0;j<slides.length;j++){ slides[j].classList.toggle('is-active', j===idx); }
    }
    function nextSlide(){ if(isPaused()) return; go(idx+1); }
    function startAuto(){ clearInterval(timer); timer = setInterval(nextSlide, 5000); }
    function stopAuto(){ userInteracted=true; clearInterval(timer); }
    if(prev) prev.addEventListener('click', function(){ go(idx-1); stopAuto(); });
    if(next) next.addEventListener('click', function(){ go(idx+1); stopAuto(); });
    dots.forEach(function(d){ d.addEventListener('click', function(){ go(parseInt(d.dataset.index,10)); stopAuto(); }); });
    track.addEventListener('touchstart', stopAuto, {once:true});
    hero.addEventListener('mouseenter', function(){ clearInterval(timer); });
    hero.addEventListener('mouseleave', function(){ if(!userInteracted) startAuto(); });
    document.addEventListener('visibilitychange', function(){ if(document.hidden) clearInterval(timer); else if(!userInteracted) startAuto(); });
    startAuto();
    // touch swipe
    var sx=0;
    track.addEventListener('touchstart', function(e){ sx=e.touches[0].clientX; }, {passive:true});
    track.addEventListener('touchend', function(e){ var dx=e.changedTouches[0].clientX - sx; if(Math.abs(dx)>50){ if(dx<0) {go(idx+1);} else {go(idx-1);} stopAuto(); } }, {passive:true});
});
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