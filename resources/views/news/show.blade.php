@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.news-detail-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.news-detail-header::before {
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

.news-detail-header .container {
    position: relative;
    z-index: 1;
}

.news-detail-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 16px;
}

.news-detail-breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color var(--ease);
}

.news-detail-breadcrumb a:hover {
    color: var(--white);
}

.news-detail-breadcrumb i {
    font-size: 10px;
}

.news-detail-header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--white);
    line-height: 1.2;
    margin-bottom: 16px;
}

.news-detail-type-badge {
    display: inline-block;
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: var(--navy);
    color: var(--white);
    padding: 8px 16px;
}

/* ============================================================
   LAYOUT
   ============================================================ */
.news-detail-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.news-detail-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   MAIN CONTENT
   ============================================================ */
.news-detail-content {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 40px;
}

.news-detail-content__title {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 800;
    letter-spacing: 0.01em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--border);
}

.news-detail-content__text {
    font-size: 15px;
    line-height: 1.75;
    color: var(--ink);
    white-space: pre-line;
}

.news-detail-content__img {
    width: 100%;
    margin: 24px 0;
    border: 2px solid var(--border);
}

.news-detail-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid var(--border);
}

.news-detail-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--mid);
}

.news-detail-meta-item i {
    font-size: 14px;
    color: var(--navy);
}

.news-detail-source {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 24px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--navy);
    text-decoration: none;
    transition: color var(--ease);
}

.news-detail-source:hover {
    color: var(--navy-dim);
}

/* ============================================================
   SIDEBAR
   ============================================================ */
.news-detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 20px;
}

.news-detail-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px;
}

.news-detail-card__title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border);
}

.news-detail-card__item {
    margin-bottom: 16px;
}

.news-detail-card__item:last-child {
    margin-bottom: 0;
}

.news-detail-card__item-title {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    display: block;
    text-decoration: none;
    transition: color var(--ease);
    margin-bottom: 4px;
}

.news-detail-card__item-title:hover {
    color: var(--navy);
}

.news-detail-card__item-date {
    font-size: 12px;
    color: var(--mid);
}

.news-detail-cta {
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 20px;
    border: none;
    text-decoration: none;
    transition: background var(--ease);
    margin-top: 24px;
    display: block;
    text-align: center;
}

.news-detail-cta:hover {
    background: var(--navy-dim);
    color: var(--white);
}

.news-detail-cta i {
    margin-right: 8px;
    font-size: 14px;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .news-detail-layout {
        grid-template-columns: 1fr;
    }
    .news-detail-sidebar {
        position: static;
        grid-row: 2;
    }
}

@media (max-width: 768px) {
    .news-detail-content {
        padding: 28px 20px;
    }
    .news-detail-card {
        padding: 20px;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="news-detail-header">
    <div class="container">
        <div class="news-detail-breadcrumb">
            <a href="{{ route('news.index') }}">
                <i class="bi bi-arrow-left"></i>
                Berita Siber
            </a>
        </div>
        <h1>{{ $news->title }}</h1>
        <span class="news-detail-type-badge">
            Berita Siber
        </span>
    </div>
</div>

{{-- NEWS DETAIL BODY --}}
<div class="news-detail-body">
    <div class="container">
        <div class="news-detail-layout">
            
            {{-- MAIN CONTENT --}}
            <div class="news-detail-content">
                <div class="news-detail-meta">
                    @if($news->date)
                    <div class="news-detail-meta-item">
                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                        <span>{{ $news->date->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Thumbnail --}}
                @if($news->thumbnail)
                <img src="{{ $news->thumbnail }}" alt="{{ $news->title }}" class="news-detail-content__img">
                @endif

                {{-- Content --}}
                <div class="news-detail-content__text">
                    {{ $news->description }}
                </div>

                {{-- Source Link --}}
                @if($news->source)
                <a href="{{ $news->source }}" target="_blank" rel="noopener noreferrer" class="news-detail-source">
                    <i class="bi bi-box-arrow-up-right"></i>
                    Lihat Sumber Asli
                </a>
                @endif
            </div>

            {{-- SIDEBAR --}}
            <aside class="news-detail-sidebar">
                
                {{-- RELATED NEWS CARD --}}
                <div class="news-detail-card">
                    <h3 class="news-detail-card__title">Berita Terkait</h3>
                    
                    @php
                        $relatedNews = \App\Models\CybersecurityNews::where('id', '!=', $news->id)->latest('date')->limit(3)->get();
                    @endphp

                    @if($relatedNews->count() > 0)
                        @foreach($relatedNews as $related)
                        <div class="news-detail-card__item">
                            <a href="{{ route('news.show', $related->id) }}" class="news-detail-card__item-title">
                                {{ $related->title }}
                            </a>
                            @if($related->date)
                            <span class="news-detail-card__item-date">
                                {{ $related->date->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p style="font-size: 13px; color: var(--mid); margin: 0;">Tidak ada berita terkait</p>
                    @endif
                </div>

                {{-- INCIDENT REPORTING CTA --}}
                <div class="news-detail-card">
                    <h3 class="news-detail-card__title">Butuh Bantuan?</h3>
                    <p style="font-size: 14px; line-height: 1.6; color: var(--mid); margin-bottom: 16px;">
                        Tim Jakarta CSIRT siap membantu jika Anda menghadapi insiden keamanan siber.
                    </p>
                    <a href="{{ route('incidents.create.step1') }}" class="news-detail-cta">
                        <i class="bi bi-exclamation-circle"></i>
                        Lapor Insiden
                    </a>
                </div>

            </aside>

        </div>
    </div>
</div>

@endsection