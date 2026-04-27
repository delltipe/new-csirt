@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.law-detail-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.law-detail-header::before {
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

.law-detail-header .container {
    position: relative;
    z-index: 1;
}

.law-detail-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 16px;
}

.law-detail-breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color var(--ease);
}

.law-detail-breadcrumb a:hover {
    color: var(--white);
}

.law-detail-breadcrumb i {
    font-size: 10px;
}

.law-detail-header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--white);
    line-height: 1.2;
    margin-bottom: 16px;
}

.law-detail-type-badge {
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
.law-detail-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.law-detail-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   MAIN CONTENT
   ============================================================ */
.law-detail-content {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 40px;
}

.law-detail-content__title {
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

.law-detail-content__text {
    font-size: 15px;
    line-height: 1.75;
    color: var(--ink);
    white-space: pre-line;
}

/* ============================================================
   SIDEBAR
   ============================================================ */
.law-detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 20px;
}

.law-detail-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px;
}

.law-detail-card__title {
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

.law-detail-meta {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.law-detail-meta-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.law-detail-meta-item__label {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--mid);
}

.law-detail-meta-item__value {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
}

.law-detail-download {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 16px;
    border: none;
    text-decoration: none;
    transition: background var(--ease);
    margin-top: 24px;
}

.law-detail-download:hover {
    background: var(--navy-dim);
    color: var(--white);
}

.law-detail-download i {
    font-size: 14px;
}

.law-detail-summary {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px;
}

.law-detail-summary__text {
    font-size: 14px;
    line-height: 1.7;
    color: var(--mid);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .law-detail-layout {
        grid-template-columns: 1fr;
    }
    .law-detail-sidebar {
        position: static;
        grid-row: 2;
    }
}

@media (max-width: 768px) {
    .law-detail-content {
        padding: 28px 20px;
    }
    .law-detail-card {
        padding: 20px;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="law-detail-header">
    <div class="container">
        <div class="law-detail-breadcrumb">
            <a href="{{ route('laws.index') }}">
                <i class="bi bi-arrow-left"></i>
                Peraturan Kebijakan
            </a>
        </div>
        <h1>{{ $post->title }}</h1>
        <span class="law-detail-type-badge">
            {{ str_replace('_', ' ', ucwords($post->regulation_type ?? 'Regulasi')) }}
        </span>
    </div>
</div>

{{-- LAW DETAIL BODY --}}
<div class="law-detail-body">
    <div class="container">
        <div class="law-detail-layout">
            
            {{-- MAIN CONTENT --}}
            <div class="law-detail-content">
                <h2 class="law-detail-content__title">Konten Regulasi</h2>
                <div class="law-detail-content__text">
                    {{ $post->description }}
                </div>
            </div>

            {{-- SIDEBAR --}}
            <aside class="law-detail-sidebar">
                
                {{-- DETAILS CARD --}}
                <div class="law-detail-card">
                    <h3 class="law-detail-card__title">Detail Regulasi</h3>
                    
                    <div class="law-detail-meta">
                        <div class="law-detail-meta-item">
                            <span class="law-detail-meta-item__label">Tipe Regulasi</span>
                            <span class="law-detail-meta-item__value">
                                {{ str_replace('_', ' ', ucwords($post->regulation_type ?? 'Regulasi')) }}
                            </span>
                        </div>

                        @if($post->date)
                        <div class="law-detail-meta-item">
                            <span class="law-detail-meta-item__label">Tanggal</span>
                            <span class="law-detail-meta-item__value">
                                {{ $post->date->format('d M Y') }}
                            </span>
                        </div>
                        @endif

                        @if($post->time)
                        <div class="law-detail-meta-item">
                            <span class="law-detail-meta-item__label">Waktu</span>
                            <span class="law-detail-meta-item__value">
                                {{ $post->time }}
                            </span>
                        </div>
                        @endif

                        <div class="law-detail-meta-item">
                            <span class="law-detail-meta-item__label">Total Unduhan</span>
                            <span class="law-detail-meta-item__value">
                                {{ number_format($post->downloadAmount ?? 0) }} kali
                            </span>
                        </div>
                    </div>

                    @if($post->file_path || $post->link)
                        <a href="{{ $post->file_path ? asset('storage/' . $post->file_path) : $post->link }}" 
                           target="_blank" 
                           class="law-detail-download">
                            <i class="bi bi-download"></i>
                            Unduh Dokumen
                        </a>
                    @endif
                </div>

            </aside>

        </div>
    </div>
</div>

@endsection