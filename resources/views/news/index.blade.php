{{--
    news/index.blade.php
    Controller: NewsController@index
    Variables:
      $news           — paginated CybersecurityNews collection
      $availableYears — distinct years for the year dropdown
--}}
@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.news-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.news-header::before {
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
.news-header .container { position: relative; z-index: 1; }
.news-header__eyebrow {
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
.news-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.news-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.news-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
}

/* ============================================================
   FILTER BAR
   Sits below the header, always visible
   ============================================================ */
.news-filter-bar {
    background: var(--white);
    border-bottom: 2px solid var(--border);
    padding: 18px 0;
    position: sticky;
    top: 68px; /* below the main nav */
    z-index: 50;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.news-filter-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

/* Search input */
.filter-search {
    display: flex;
    flex: 1;
    min-width: 220px;
    height: 42px;
    border: 1px solid var(--border);
}
.filter-search input {
    flex: 1;
    border: none;
    outline: none;
    padding: 0 14px;
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--ink);
    background: var(--mist);
}
.filter-search input::placeholder { color: var(--mid); }
.filter-search button {
    width: 42px;
    background: var(--ink);
    color: var(--white);
    border: none;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background var(--ease);
    flex-shrink: 0;
}
.filter-search button:hover { background: var(--navy); }

/* Date selects */
.filter-select {
    height: 42px;
    border: 1px solid var(--border);
    background: var(--mist);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 13.5px;
    padding: 0 32px 0 12px;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236B7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    border-radius: 0;
    min-width: 100px;
    transition: border-color var(--ease);
}
.filter-select:focus { border-color: var(--navy); }

/* Reset link */
.filter-reset {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--mid);
    text-decoration: none;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color var(--ease);
    padding: 0 4px;
}
.filter-reset:hover { color: var(--navy); }
.filter-reset i { font-size: 11px; }

/* Active filters badge */
.filter-active-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--navy-tint);
    border: 1px solid var(--navy);
    color: var(--navy);
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    white-space: nowrap;
}

/* ============================================================
   RESULTS META ROW
   ============================================================ */
.news-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 8px;
}
.news-meta-count {
    font-size: 13.5px;
    color: var(--mid);
}
.news-meta-count strong { color: var(--ink); }

/* ============================================================
   NEWS GRID — mirrors the homepage news-grid exactly
   ============================================================ */
.news-list-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border: 1px solid var(--border);
    margin-bottom: 40px;
}
.news-list-card {
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    transition: background var(--ease);
    position: relative;
    overflow: hidden;
}
/* Bottom 3px blue rule on hover — same as homepage */
.news-list-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: var(--navy);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.2s ease;
}
.news-list-card:hover { background: var(--mist); }
.news-list-card:hover::after { transform: scaleX(1); }

/* Remove right border on last in each row */
.news-list-card:nth-child(3n) { border-right: none; }

.news-list-card__img-wrap { overflow: hidden; }
.news-list-card__img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    filter: grayscale(15%);
    transition: transform 0.3s ease, filter 0.3s ease;
}
.news-list-card:hover .news-list-card__img {
    transform: scale(1.03);
    filter: grayscale(0%);
}

/* No thumbnail placeholder */
.news-list-card__no-img {
    width: 100%;
    height: 200px;
    background: var(--mist);
    display: flex;
    align-items: center;
    justify-content: center;
}
.news-list-card__no-img i { font-size: 36px; color: var(--border); }

.news-list-card__body { padding: 20px; flex: 1; display: flex; flex-direction: column; }

.news-list-card__date {
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--navy);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.news-list-card__date::before {
    content: '';
    display: block;
    width: 14px; height: 2px;
    background: var(--navy);
    flex-shrink: 0;
}

.news-list-card__title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.25;
    margin-bottom: 10px;
    transition: color var(--ease);
}
.news-list-card:hover .news-list-card__title { color: var(--navy); }

.news-list-card__excerpt {
    font-size: 13.5px;
    color: var(--mid);
    line-height: 1.65;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 14px;
}

.news-list-card__footer {
    margin-top: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid var(--border);
}
.news-list-card__read-more {
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: var(--navy);
    display: flex;
    align-items: center;
    gap: 4px;
}
.news-list-card__read-more i { font-size: 10px; }
.news-list-card__ago {
    font-size: 11.5px;
    color: var(--mid);
}

/* Empty / no results */
.news-empty {
    grid-column: 1 / -1;
    padding: 80px 24px;
    text-align: center;
    background: var(--white);
    border: 1px solid var(--border);
    border-top: none;
}
.news-empty i { font-size: 44px; color: var(--border); display: block; margin-bottom: 14px; }
.news-empty p { font-size: 15px; color: var(--mid); margin-bottom: 16px; }
.news-empty a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--navy);
    text-decoration: none;
    border-bottom: 1px solid var(--navy);
    padding-bottom: 2px;
}

/* ============================================================
   PAGINATION
   ============================================================ */
nav[role="navigation"] ul {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0; margin: 0;
    flex-wrap: wrap;
    justify-content: center;
}
nav[role="navigation"] li a,
nav[role="navigation"] li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px; height: 36px;
    padding: 0 10px;
    font-size: 13px; font-weight: 500;
    color: var(--ink);
    border: 1px solid var(--border);
    background: var(--white);
    text-decoration: none;
    transition: background var(--ease), border-color var(--ease), color var(--ease);
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
.pagination-wrap { display: flex; justify-content: center; }

/* ============================================================
   BODY WRAPPER
   ============================================================ */
.news-body { padding: 48px 0 80px; background: var(--mist); }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .news-list-grid { grid-template-columns: 1fr 1fr; }
    .news-list-card:nth-child(3n) { border-right: 1px solid var(--border); }
    .news-list-card:nth-child(2n) { border-right: none; }
}
@media (max-width: 600px) {
    .news-list-grid { grid-template-columns: 1fr; }
    .news-list-card { border-right: none !important; }
    .news-filter-form { flex-direction: column; align-items: stretch; }
    .filter-search { min-width: unset; }
    .filter-select { width: 100%; }
}
</style>


{{-- PAGE HEADER --}}
<div class="news-header">
    <div class="container">
        <div class="news-header__eyebrow">
            <i class="bi bi-newspaper" aria-hidden="true"></i>
            JakartaProv-CSIRT
        </div>
        <h1>Berita Siber</h1>
        <p class="news-header__sub">
            Informasi dan edukasi terbaru seputar keamanan siber di lingkungan Pemprov DKI Jakarta.
        </p>
    </div>
</div>


{{-- FILTER BAR --}}
<div class="news-filter-bar" role="search" aria-label="Filter berita">
    <div class="container">
        <form class="news-filter-form"
              method="GET"
              action="{{ route('news.index') }}"
              id="filter-form">

            {{-- Keyword search --}}
            <div class="filter-search">
                <input type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari berita..."
                       aria-label="Kata kunci pencarian">
                <button type="submit" aria-label="Cari">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </div>

            {{-- Day --}}
            <select name="day"
                    class="filter-select"
                    aria-label="Filter hari"
                    onchange="this.form.submit()">
                <option value="">Hari</option>
                @for($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>
                    {{ str_pad($d, 2, '0', STR_PAD_LEFT) }}
                </option>
                @endfor
            </select>

            {{-- Month --}}
            <select name="month"
                    class="filter-select"
                    aria-label="Filter bulan"
                    onchange="this.form.submit()">
                <option value="">Bulan</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bulan)
                <option value="{{ $idx + 1 }}" {{ request('month') == $idx + 1 ? 'selected' : '' }}>
                    {{ $bulan }}
                </option>
                @endforeach
            </select>

            {{-- Year — dynamic from DB --}}
            <select name="year"
                    class="filter-select"
                    aria-label="Filter tahun"
                    onchange="this.form.submit()">
                <option value="">Tahun</option>
                @foreach($availableYears as $yr)
                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                    {{ $yr }}
                </option>
                @endforeach
            </select>

            {{-- Reset — only shown when any filter is active --}}
            @if(request('q') || request('day') || request('month') || request('year'))
            <a href="{{ route('news.index') }}" class="filter-reset">
                <i class="bi bi-x-circle"></i> Reset
            </a>
            @endif

        </form>
    </div>
</div>


{{-- NEWS BODY --}}
<div class="news-body">
    <div class="container">

        {{-- Results meta row --}}
        <div class="news-meta-row">
            <p class="news-meta-count">
                Menampilkan <strong>{{ $news->firstItem() ?? 0 }}–{{ $news->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $news->total() }}</strong> berita
                @if(request('q'))
                    untuk <strong>"{{ request('q') }}"</strong>
                @endif
            </p>

            {{-- Active filter badges --}}
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                @if(request('q'))
                <span class="filter-active-badge">
                    <i class="bi bi-search" style="font-size:10px;"></i>
                    {{ request('q') }}
                </span>
                @endif
                @if(request('day') || request('month') || request('year'))
                <span class="filter-active-badge">
                    <i class="bi bi-calendar3" style="font-size:10px;"></i>
                    {{ request('day') ? str_pad(request('day'), 2, '0', STR_PAD_LEFT).'/' : '' }}{{ request('month') ? str_pad(request('month'), 2, '0', STR_PAD_LEFT).'/' : '' }}{{ request('year') ?? '' }}
                </span>
                @endif
            </div>
        </div>

        {{-- Grid --}}
        @if($news->isEmpty())
        <div class="news-empty">
            <i class="bi bi-newspaper" aria-hidden="true"></i>
            <p>Tidak ada berita yang cocok dengan filter Anda.</p>
            <a href="{{ route('news.index') }}">
                <i class="bi bi-arrow-left"></i> Lihat semua berita
            </a>
        </div>
        @else
        <div class="news-list-grid">
            @foreach($news as $article)
            <a href="{{ route('news.show', $article->id) }}" class="news-list-card">

                {{-- Thumbnail --}}
                @if($article->thumbnail)
                <div class="news-list-card__img-wrap">
                    <img class="news-list-card__img"
                         src="{{ $article->thumbnail }}"
                         alt="{{ $article->title }}"
                         loading="lazy"
                         onerror="this.closest('.news-list-card__img-wrap').innerHTML='<div class=\'news-list-card__no-img\'><i class=\'bi bi-image\'></i></div>'">
                </div>
                @else
                <div class="news-list-card__no-img">
                    <i class="bi bi-image" aria-hidden="true"></i>
                </div>
                @endif

                <div class="news-list-card__body">
                    {{-- Date --}}
                    @if($article->date)
                    <div class="news-list-card__date">
                        {{ $article->date->format('d M Y') }}
                    </div>
                    @endif

                    {{-- Title --}}
                    <h3 class="news-list-card__title">{{ $article->title }}</h3>

                    {{-- Excerpt --}}
                    <p class="news-list-card__excerpt">
                        {{ Str::limit($article->description, 120) }}
                    </p>

                    <div class="news-list-card__footer">
                        <span class="news-list-card__read-more">
                            Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                        </span>
                        @if($article->date)
                        <span class="news-list-card__ago">
                            {{ $article->date->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                </div>

            </a>
            @endforeach
        </div>
        @endif

        {{-- Pagination --}}
        <div class="pagination-wrap">
            {{ $news->links() }}
        </div>

    </div>
</div>

@endsection