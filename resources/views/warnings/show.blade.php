@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.warning-detail-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.warning-detail-header::before {
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

.warning-detail-header .container {
    position: relative;
    z-index: 1;
}

.warning-detail-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 16px;
}

.warning-detail-breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color var(--ease);
}

.warning-detail-breadcrumb a:hover {
    color: var(--white);
}

.warning-detail-breadcrumb i {
    font-size: 10px;
}

.warning-detail-header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--white);
    line-height: 1.2;
    margin-bottom: 16px;
}

.warning-detail-type-badge {
    display: inline-block;
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: #dc2626;
    color: var(--white);
    padding: 8px 16px;
}

/* ============================================================
   LAYOUT
   ============================================================ */
.warning-detail-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.warning-detail-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   MAIN CONTENT
   ============================================================ */
.warning-detail-content {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 40px;
}

.warning-detail-content__title {
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

.warning-detail-content__text {
    font-size: 15px;
    line-height: 1.75;
    color: var(--ink);
    white-space: pre-line;
}

.warning-detail-content__img {
    width: 100%;
    margin: 24px 0;
    border: 2px solid var(--border);
}

.warning-detail-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid var(--border);
}

.warning-detail-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--mid);
}

.warning-detail-meta-item i {
    font-size: 14px;
    color: #dc2626;
}

.warning-detail-source {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 24px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #dc2626;
    text-decoration: none;
    transition: color var(--ease);
}

.warning-detail-source:hover {
    color: #ef4444;
}

/* ============================================================
   SIDEBAR
   ============================================================ */
.warning-detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 20px;
}

.warning-detail-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px;
}

.warning-detail-card__title {
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

.warning-detail-card__item {
    margin-bottom: 16px;
}

.warning-detail-card__item:last-child {
    margin-bottom: 0;
}

.warning-detail-card__item-title {
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

.warning-detail-card__item-title:hover {
    color: #dc2626;
}

.warning-detail-card__item-date {
    font-size: 12px;
    color: var(--mid);
}

.warning-detail-cta {
    background: #dc2626;
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

.warning-detail-cta:hover {
    background: #ef4444;
    color: var(--white);
}

.warning-detail-cta i {
    margin-right: 8px;
    font-size: 14px;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .warning-detail-layout {
        grid-template-columns: 1fr;
    }
    .warning-detail-sidebar {
        position: static;
        grid-row: 2;
    }
}

@media (max-width: 768px) {
    .warning-detail-content {
        padding: 28px 20px;
    }
    .warning-detail-card {
        padding: 20px;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="warning-detail-header">
    <div class="container">
        <div class="warning-detail-breadcrumb">
            <a href="{{ route('warnings.index') }}">
                <i class="bi bi-arrow-left"></i>
                Peringatan Keamanan
            </a>
        </div>
        <h1>{{ $warning->title }}</h1>
        <span class="warning-detail-type-badge">
            Alert Keamanan
        </span>
    </div>
</div>

{{-- WARNING DETAIL BODY --}}
<div class="warning-detail-body">
    <div class="container">
        <div class="warning-detail-layout">
            
            {{-- MAIN CONTENT --}}
            <div class="warning-detail-content">
                <div class="warning-detail-meta">
                    @if($warning->date)
                    <div class="warning-detail-meta-item">
                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                        <span>{{ $warning->date->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Thumbnail --}}
                @if($warning->thumbnail)
                <img src="{{ $warning->thumbnail }}" alt="{{ $warning->title }}" class="warning-detail-content__img">
                @endif

                {{-- Content --}}
                <div class="warning-detail-content__text">
                    {{ $warning->description }}
                </div>

                {{-- Source Link --}}
                @if($warning->source)
                <a href="{{ $warning->source }}" target="_blank" rel="noopener noreferrer" class="warning-detail-source">
                    <i class="bi bi-box-arrow-up-right"></i>
                    Lihat Sumber Referensi Resmi
                </a>
                @endif
            </div>

            {{-- SIDEBAR --}}
            <aside class="warning-detail-sidebar">
                
                {{-- RELATED WARNINGS CARD --}}
                <div class="warning-detail-card">
                    <h3 class="warning-detail-card__title">Alert Terkait</h3>
                    
                    @php
                        $relatedWarnings = \App\Models\WarningPost::where('id', '!=', $warning->id)->latest('date')->limit(3)->get();
                    @endphp

                    @if($relatedWarnings->count() > 0)
                        @foreach($relatedWarnings as $related)
                        <div class="warning-detail-card__item">
                            <a href="{{ route('warnings.show', $related->id) }}" class="warning-detail-card__item-title">
                                {{ $related->title }}
                            </a>
                            @if($related->date)
                            <span class="warning-detail-card__item-date">
                                {{ $related->date->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p style="font-size: 13px; color: var(--mid); margin: 0;">Tidak ada alert terkait</p>
                    @endif
                </div>

                {{-- INCIDENT REPORTING CTA --}}
                <div class="warning-detail-card">
                    <h3 class="warning-detail-card__title">Sistem Anda Terpengaruh?</h3>
                    <p style="font-size: 14px; line-height: 1.6; color: var(--mid); margin-bottom: 16px;">
                        Laporkan jika sistem atau infrastruktur Anda terpengaruh oleh ancaman ini.
                    </p>
                    <a href="{{ route('incidents.create.step1') }}" class="warning-detail-cta">
                        <i class="bi bi-exclamation-circle"></i>
                        Lapor Insiden
                    </a>
                </div>

            </aside>

        </div>
    </div>
</div>

@endsection