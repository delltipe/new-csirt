@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.guides-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.guides-header::before {
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

.guides-header .container { position: relative; z-index: 1; }

.guides-header__eyebrow {
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

.guides-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}

.guides-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}

.guides-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
}

/* ============================================================
   LAYOUT - Sidebar + Content
   ============================================================ */
.guides-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.guides-layout {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   SIDEBAR FILTERS
   ============================================================ */
.filter-sidebar {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 32px;
    position: sticky;
    top: 20px;
}

.filter-sidebar__title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.02em;
    color: var(--ink);
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--border);
}

.filter-group {
    margin-bottom: 20px;
}

.filter-label {
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
    display: block;
}

.filter-input {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid var(--border);
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--ink);
    background: var(--white);
    transition: border-color var(--ease);
}

.filter-input:focus {
    outline: none;
    border-color: var(--navy);
}

.filter-btn {
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 14px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
    margin-top: 24px;
}

.filter-btn:hover {
    background: var(--navy-dim);
}

/* ============================================================
   GUIDE CARDS
   ============================================================ */
.guides-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.guide-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px 32px;
    text-decoration: none;
    color: inherit;
    transition: all var(--ease);
    position: relative;
    display: block;
}

.guide-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--navy);
    transform: scaleY(0);
    transform-origin: bottom;
    transition: transform var(--ease);
}

.guide-card:hover {
    border-color: var(--navy);
    background: var(--navy-tint);
}

.guide-card:hover::before {
    transform: scaleY(1);
}

.guide-card__header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 14px;
}

.guide-card__title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.3;
    flex: 1;
    transition: color var(--ease);
}

.guide-card:hover .guide-card__title {
    color: var(--navy);
}

.guide-card__badge {
    font-family: var(--font-display);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: var(--navy);
    color: var(--white);
    padding: 6px 12px;
    white-space: nowrap;
    flex-shrink: 0;
}

.guide-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.guide-card__meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--mid);
}

.guide-card__meta-item i {
    font-size: 12px;
    color: var(--navy);
}

.guide-card__meta-item strong {
    color: var(--ink);
    font-weight: 600;
}

.guide-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 16px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--navy);
}

.guide-card__cta i {
    font-size: 10px;
    transition: transform var(--ease);
}

.guide-card:hover .guide-card__cta i {
    transform: translateX(3px);
}

/* Empty state */
.guides-empty {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 80px 32px;
    text-align: center;
}

.guides-empty i {
    font-size: 44px;
    color: var(--border);
    display: block;
    margin-bottom: 14px;
}

.guides-empty p {
    font-size: 15px;
    color: var(--mid);
}

/* ============================================================
   PAGINATION
   ============================================================ */
.guides-pagination {
    display: flex;
    justify-content: center;
    margin-top: 32px;
}

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
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 500;
    color: var(--ink);
    border: 2px solid var(--border);
    background: var(--white);
    text-decoration: none;
    transition: all var(--ease);
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

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1024px) {
    .guides-layout {
        grid-template-columns: 280px 1fr;
        gap: 24px;
    }
    .filter-sidebar {
        padding: 24px;
    }
}

@media (max-width: 768px) {
    .guides-layout {
        grid-template-columns: 1fr;
    }
    .filter-sidebar {
        position: static;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="guides-header">
    <div class="container">
        <div class="guides-header__eyebrow">
            <i class="bi bi-book" aria-hidden="true"></i>
            Panduan & Sumber Daya
        </div>
        <h1>Panduan Teknis Keamanan Siber</h1>
        <p class="guides-header__sub">
            Panduan komprehensif dan sumber daya teknis untuk meningkatkan keamanan siber di organisasi Anda.
        </p>
    </div>
</div>

{{-- GUIDES BODY --}}
<div class="guides-body">
    <div class="container">
        <div class="guides-layout">
            
            {{-- SIDEBAR FILTERS --}}
            <aside class="filter-sidebar">
                <h2 class="filter-sidebar__title">Filter Pencarian</h2>
                
                <form method="GET" action="{{ route('guides.index') }}">
                    <div class="filter-group">
                        <label for="author" class="filter-label">Cari Pembuat</label>
                        <input 
                            type="text" 
                            id="author" 
                            name="author" 
                            class="filter-input"
                            placeholder="Nama pembuat..."
                            value="{{ request('author') }}"
                        >
                    </div>

                    <button type="submit" class="filter-btn">
                        Cari Panduan
                    </button>
                </form>
            </aside>

            {{-- GUIDES CONTENT --}}
            <div class="guides-content">
                @forelse($guides as $guide)
                    <a href="{{ route('guides.show', $guide) }}" class="guide-card">
                        <div class="guide-card__header">
                            <h3 class="guide-card__title">{{ $guide->title }}</h3>
                            <span class="guide-card__badge">Panduan Teknis</span>
                        </div>

                        <div class="guide-card__meta">
                            <div class="guide-card__meta-item">
                                <i class="bi bi-person" aria-hidden="true"></i>
                                <strong>{{ $guide->author }}</strong>
                            </div>
                        </div>

                        <span class="guide-card__cta">
                            Lihat Panduan <i class="bi bi-arrow-right"></i>
                        </span>
                    </a>
                @empty
                    <div class="guides-empty">
                        <i class="bi bi-book-x" aria-hidden="true"></i>
                        <p>Tidak ada panduan ditemukan</p>
                    </div>
                @endforelse

                {{-- PAGINATION --}}
                @if($guides->hasPages())
                <div class="guides-pagination">
                    {{ $guides->appends(request()->query())->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection
