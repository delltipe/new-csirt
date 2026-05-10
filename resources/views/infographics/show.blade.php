{{--
    infographics/show.blade.php
    Controller: InfographicController@show
    Variable:   $infographic  (single Infographic model — fields: id, title, thumbnail)

    NOTE: The primary UX is the lightbox on the index page.
    This page exists because the route /infographics/{infographic} is registered
    and some users may navigate here directly (e.g. from a shared URL).
    It shows the image large with the title and a back link.
--}}
@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   SHOW PAGE HEADER
   ============================================================ */
.infographic-show-header {
    background: var(--ink);
    padding: 36px 0 32px;
    position: relative;
    overflow: hidden;
}
.infographic-show-header::before {
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
.infographic-show-header .container { position: relative; z-index: 1; }

.infographic-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    margin-bottom: 18px;
    transition: color var(--ease);
}
.infographic-back:hover { color: rgba(255,255,255,0.8); }
.infographic-back i { font-size: 11px; }

.infographic-show-header h1 {
    font-family: var(--font-display);
    font-size: clamp(24px, 4vw, 40px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1.05;
    max-width: 760px;
}

/* ============================================================
   BODY
   ============================================================ */
.infographic-show-body {
    padding: 48px 0 80px;
    background: var(--mist);
}

.infographic-show-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--navy);
    max-width: 860px;
    margin: 0 auto;
}

/* Full image — tall, not cropped */
.infographic-show-img {
    width: 100%;
    display: block;
    max-height: 80vh;
    object-fit: contain;
    background: var(--ink); /* dark bg so white-bg infographics still look sharp */
}

.infographic-show-footer {
    padding: 20px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    border-top: 1px solid var(--border);
}

.infographic-show-title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
}

.infographic-show-actions {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 11px 20px;
    text-decoration: none;
    transition: background var(--ease);
}
.btn-download:hover { background: var(--navy-dim); color: var(--white); }

.btn-back-gallery {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: transparent;
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
    padding: 10px 20px;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
}
.btn-back-gallery:hover { background: var(--navy-tint); }

@media (max-width: 640px) {
    .infographic-show-footer { flex-direction: column; align-items: flex-start; }
    .infographic-show-actions { width: 100%; }
    .btn-download, .btn-back-gallery { flex: 1; justify-content: center; }
}
</style>


{{-- HEADER --}}
<div class="infographic-show-header">
    <div class="container">
        <a href="{{ route('infographics.index') }}" class="infographic-back">
            <i class="bi bi-arrow-left"></i> Semua Infografis
        </a>
        <h1>{{ $infographic->title }}</h1>
    </div>
</div>


{{-- BODY --}}
<div class="infographic-show-body">
    <div class="container">
        <div class="infographic-show-card">

            {{-- Full-size image --}}
            <img class="infographic-show-img"
                 src="{{ $infographic->thumbnail }}"
                 alt="{{ $infographic->title }}"
                 onerror="this.style.minHeight='200px';this.style.background='var(--mist)'">

            {{-- Footer: title + actions --}}
            <div class="infographic-show-footer">
                <div class="infographic-show-title">{{ $infographic->title }}</div>
                <div class="infographic-show-actions">
                    <a href="{{ $infographic->thumbnail }}"
                       download
                       class="btn-download"
                       target="_blank"
                       rel="noopener">
                        <i class="bi bi-download"></i> Unduh
                    </a>
                    <a href="{{ route('infographics.index') }}" class="btn-back-gallery">
                        <i class="bi bi-grid"></i> Kembali ke Galeri
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection