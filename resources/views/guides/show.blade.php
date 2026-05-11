@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.guide-detail-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.guide-detail-header::before {
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

.guide-detail-header .container {
    position: relative;
    z-index: 1;
}

.guide-detail-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 16px;
}

.guide-detail-breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color var(--ease);
}

.guide-detail-breadcrumb a:hover {
    color: var(--white);
}

.guide-detail-breadcrumb i {
    font-size: 10px;
}

.guide-detail-header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--white);
    line-height: 1.2;
    margin-bottom: 16px;
}

.guide-detail-type-badge {
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
.guide-detail-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.guide-detail-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   MAIN CONTENT
   ============================================================ */
.guide-detail-content {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 40px;
}

.guide-detail-content__title {
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

.guide-detail-content__text {
    font-size: 15px;
    line-height: 1.75;
    color: var(--ink);
    white-space: pre-line;
}

.guide-detail-content__empty {
    font-size: 15px;
    line-height: 1.75;
    color: var(--mid);
    font-style: italic;
}

/* ============================================================
   SIDEBAR
   ============================================================ */
.guide-detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 20px;
}

.guide-detail-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px;
}

.guide-detail-card__title {
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

.guide-detail-meta {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.guide-detail-meta-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.guide-detail-meta-item__label {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--mid);
}

.guide-detail-meta-item__value {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
}

.guide-detail-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 24px;
}

.guide-detail-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 16px;
    border: 2px solid var(--navy);
    text-decoration: none;
    transition: all var(--ease);
    cursor: pointer;
}

.guide-detail-btn:hover {
    background: transparent;
    color: var(--navy);
}

.guide-detail-btn i {
    font-size: 14px;
}

.guide-detail-btn--secondary {
    background: transparent;
    color: var(--navy);
    border-color: var(--navy);
}

.guide-detail-btn--secondary:hover {
    background: var(--navy);
    color: var(--white);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .guide-detail-layout {
        grid-template-columns: 1fr;
    }
    .guide-detail-sidebar {
        position: static;
        grid-row: 2;
    }
}

@media (max-width: 768px) {
    .guide-detail-content {
        padding: 28px 20px;
    }
    .guide-detail-card {
        padding: 20px;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="guide-detail-header">
    <div class="container">
        <div class="guide-detail-breadcrumb">
            <a href="{{ route('guides.index') }}">
                <i class="bi bi-arrow-left"></i>
                Panduan Teknis
            </a>
        </div>
        <h1>{{ $guide->title }}</h1>
        <span class="guide-detail-type-badge">
            Panduan Teknis
        </span>
    </div>
</div>

{{-- GUIDE DETAIL BODY --}}
<div class="guide-detail-body">
    <div class="container">
        <div class="guide-detail-layout">
            
            {{-- MAIN CONTENT --}}
            <div class="guide-detail-content">
                <h2 class="guide-detail-content__title">Tentang Panduan Ini</h2>
                <p class="guide-detail-content__text">
                    Panduan teknis keamanan siber yang disusun oleh <strong>{{ $guide->author }}</strong>.
                    @if($guide->link || $guide->file_path)
                        Akses panduan melalui tombol di samping untuk informasi lengkap dan detail teknis.
                    @else
                        Hubungi pembuat panduan untuk informasi lebih lanjut.
                    @endif
                </p>
            </div>

            {{-- SIDEBAR --}}
            <aside class="guide-detail-sidebar">
                
                {{-- DETAILS CARD --}}
                <div class="guide-detail-card">
                    <h3 class="guide-detail-card__title">Detail Panduan</h3>
                    
                    <div class="guide-detail-meta">
                        <div class="guide-detail-meta-item">
                            <span class="guide-detail-meta-item__label">Pembuat Panduan</span>
                            <span class="guide-detail-meta-item__value">
                                {{ $guide->author }}
                            </span>
                        </div>

                        <div class="guide-detail-meta-item">
                            <span class="guide-detail-meta-item__label">Tipe Konten</span>
                            <span class="guide-detail-meta-item__value">
                                Panduan Teknis
                            </span>
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="guide-detail-actions">
                        @if($guide->file_path)
                        <a href="{{ $guide->file_path }}" 
                           class="guide-detail-btn"
                           download
                           aria-label="Unduh panduan">
                            <i class="bi bi-download" aria-hidden="true"></i>
                            Unduh Panduan
                        </a>
                        @endif

                        @if($guide->link)
                        <a href="{{ $guide->link }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="guide-detail-btn {{ $guide->file_path ? 'guide-detail-btn--secondary' : '' }}"
                           aria-label="Buka sumber daya eksternal">
                            <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                            Buka Sumber Daya
                        </a>
                        @endif
                    </div>
                </div>

            </aside>

        </div>
    </div>
</div>

@endsection
