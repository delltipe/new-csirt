@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER - Same style as Events
   ============================================================ */
.laws-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.laws-header::before {
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
.laws-header .container { position: relative; z-index: 1; }
.laws-header__eyebrow {
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
.laws-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.laws-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.laws-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
}

/* ============================================================
   LAYOUT - Sidebar + Content
   ============================================================ */
.laws-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.laws-layout {
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

.filter-input,
.filter-select {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid var(--border);
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--ink);
    background: var(--white);
    transition: border-color var(--ease);
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--navy);
}

.filter-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230A0F1A' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
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
   LAW CARDS
   ============================================================ */
.laws-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.law-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 28px 32px;
    text-decoration: none;
    color: inherit;
    transition: all var(--ease);
    position: relative;
    display: block;
}

.law-card::before {
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

.law-card:hover {
    border-color: var(--navy);
    background: var(--navy-tint);
}

.law-card:hover::before {
    transform: scaleY(1);
}

.law-card__header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 14px;
}

.law-card__title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.3;
    flex: 1;
    transition: color var(--ease);
}

.law-card:hover .law-card__title {
    color: var(--navy);
}

.law-card__type {
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

.law-card__desc {
    font-size: 14px;
    line-height: 1.65;
    color: var(--mid);
    margin-bottom: 16px;
}

.law-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.law-card__meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--mid);
}

.law-card__meta-item i {
    font-size: 12px;
    color: var(--navy);
}

.law-card__meta-item strong {
    color: var(--ink);
    font-weight: 600;
}

.law-card__cta {
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

.law-card__cta i {
    font-size: 10px;
    transition: transform var(--ease);
}

.law-card:hover .law-card__cta i {
    transform: translateX(3px);
}

/* Empty state */
.laws-empty {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 80px 32px;
    text-align: center;
}

.laws-empty i {
    font-size: 44px;
    color: var(--border);
    display: block;
    margin-bottom: 14px;
}

.laws-empty p {
    font-size: 15px;
    color: var(--mid);
}

/* ============================================================
   PAGINATION
   ============================================================ */
.laws-pagination {
    display: flex;
    justify-content: center;
    margin-top: 32px;
}

.laws-pagination nav {
    display: flex;
    justify-content: center;
}

.laws-pagination nav ul,
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
    .laws-layout {
        grid-template-columns: 280px 1fr;
        gap: 24px;
    }
    .filter-sidebar {
        padding: 24px;
    }
}

@media (max-width: 768px) {
    .laws-layout {
        grid-template-columns: 1fr;
    }
    .filter-sidebar {
        position: static;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="laws-header">
    <div class="container">
        <div class="laws-header__eyebrow">
            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
            Publikasi
        </div>
        <h1>Peraturan Kebijakan</h1>
        <p class="laws-header__sub">
            Undang-undang dan regulasi keamanan siber Indonesia
        </p>
    </div>
</div>

{{-- LAWS BODY --}}
<div class="laws-body">
    <div class="container">
        <div class="laws-layout">
            
            {{-- SIDEBAR FILTERS --}}
            <aside class="filter-sidebar">
                <h2 class="filter-sidebar__title">Filter Pencarian</h2>
                
                <form method="GET" action="{{ route('laws.index') }}">
                    <div class="filter-group">
                        <label for="keyword" class="filter-label">Kata Pencarian</label>
                        <input 
                            type="text" 
                            id="keyword" 
                            name="keyword" 
                            class="filter-input"
                            placeholder="Cari peraturan..."
                            value="{{ request('keyword') }}"
                        >
                    </div>

                    <div class="filter-group">
                        <label for="type" class="filter-label">Pilih Tipe Peraturan</label>
                        <select id="type" name="type" class="filter-select">
                            <option value="">-- Pilih Tipe Peraturan --</option>
                            <option value="undang_undang" {{ request('type') == 'undang_undang' ? 'selected' : '' }}>Undang-Undang</option>
                            <option value="peraturan_pemerintah" {{ request('type') == 'peraturan_pemerintah' ? 'selected' : '' }}>Peraturan Pemerintah</option>
                            <option value="peraturan_presiden" {{ request('type') == 'peraturan_presiden' ? 'selected' : '' }}>Peraturan Presiden</option>
                            <option value="peraturan_menteri" {{ request('type') == 'peraturan_menteri' ? 'selected' : '' }}>Peraturan Menteri</option>
                            <option value="peraturan_daerah" {{ request('type') == 'peraturan_daerah' ? 'selected' : '' }}>Peraturan Daerah</option>
                            <option value="keputusan" {{ request('type') == 'keputusan' ? 'selected' : '' }}>Keputusan</option>
                            <option value="surat_edaran" {{ request('type') == 'surat_edaran' ? 'selected' : '' }}>Surat Edaran</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="year" class="filter-label">Pilih Tahun</label>
                        <select id="year" name="year" class="filter-select">
                            <option value="">-- Pilih Tahun --</option>
                            @for($y = date('Y'); $y >= 2015; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="filter-btn">
                        Cari Peraturan
                    </button>
                </form>
            </aside>

            {{-- LAWS CONTENT --}}
            <div class="laws-content">
                @forelse($posts as $post)
                    <a href="{{ route('laws.show', $post) }}" class="law-card">
                        <div class="law-card__header">
                            <h3 class="law-card__title">{{ $post->title }}</h3>
                            <span class="law-card__type">
                                {{ str_replace('_', ' ', ucwords($post->regulation_type ?? 'Regulasi')) }}
                            </span>
                        </div>

                        <p class="law-card__desc">
                            {{ Str::limit($post->description, 200) }}
                        </p>

                        <div class="law-card__meta">
                            @if($post->date)
                            <div class="law-card__meta-item">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                <span>{{ $post->date->format('d M Y') }}</span>
                            </div>
                            @endif

                            @if($post->time)
                            <div class="law-card__meta-item">
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                <span>{{ $post->time }}</span>
                            </div>
                            @endif

                            <div class="law-card__meta-item">
                                <i class="bi bi-download" aria-hidden="true"></i>
                                <strong>{{ $post->downloadAmount ?? 0 }}</strong>
                                <span>Unduhan</span>
                            </div>
                        </div>

                        <span class="law-card__cta">
                            Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                        </span>
                    </a>
                @empty
                    <div class="laws-empty">
                        <i class="bi bi-file-earmark-x" aria-hidden="true"></i>
                        <p>Tidak ada peraturan ditemukan</p>
                    </div>
                @endforelse

                {{-- PAGINATION --}}
                @if($posts->hasPages())
                <div class="laws-pagination">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection