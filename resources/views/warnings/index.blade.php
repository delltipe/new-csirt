@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.warnings-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.warnings-header::before {
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

.warnings-header .container { position: relative; z-index: 1; }

.warnings-header__eyebrow {
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

.warnings-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}

.warnings-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}

.warnings-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
}

/* ============================================================
   BODY & GRID
   ============================================================ */
.warnings-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

.warnings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

/* ============================================================
   WARNING CARD
   ============================================================ */
.warning-card {
    background: var(--white);
    border: 2px solid var(--border);
    overflow: hidden;
    transition: all var(--ease);
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    position: relative;
}

.warning-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc2626, #ef4444);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform var(--ease);
    z-index: 1;
}

.warning-card:hover {
    border-color: #dc2626;
}

.warning-card:hover::before {
    transform: scaleX(1);
}

.warning-card__image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    background: var(--mist);
}

.warning-card__no-image {
    width: 100%;
    height: 200px;
    background: var(--mist);
    display: flex;
    align-items: center;
    justify-content: center;
}

.warning-card__no-image i {
    font-size: 48px;
    color: var(--border);
}

.warning-card__body {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.warning-card__title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.3;
    margin-bottom: 12px;
    transition: color var(--ease);
}

.warning-card:hover .warning-card__title {
    color: #dc2626;
}

.warning-card__excerpt {
    font-size: 13.5px;
    line-height: 1.6;
    color: var(--mid);
    margin-bottom: 16px;
    flex: 1;
}

.warning-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.warning-card__cta {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #dc2626;
    transition: color var(--ease);
}

.warning-card:hover .warning-card__cta {
    color: #ef4444;
}

.warning-card__date {
    font-size: 12px;
    color: var(--mid);
}

/* Empty state */
.warnings-empty {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 80px 32px;
    text-align: center;
}

.warnings-empty i {
    font-size: 44px;
    color: var(--border);
    display: block;
    margin-bottom: 14px;
}

.warnings-empty p {
    font-size: 15px;
    color: var(--mid);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .warnings-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}

@media (max-width: 600px) {
    .warnings-grid {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- PAGE HEADER --}}
<div class="warnings-header">
    <div class="container">
        <div class="warnings-header__eyebrow">
            <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
            Alert & Notifikasi
        </div>
        <h1>Peringatan Keamanan</h1>
        <p class="warnings-header__sub">
            Notifikasi ancaman siber terbaru sesuai standar teknis Jakarta CSIRT. Tetap waspada terhadap ancaman keamanan cyber.
        </p>
    </div>
</div>

{{-- WARNINGS BODY --}}
<div class="warnings-body">
    <div class="container">
        @if($warnings->isEmpty())
        <div class="warnings-empty">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            <p>Tidak ada peringatan keamanan saat ini.</p>
            <p style="font-size: 13px; margin-top: 8px;">Keadaan keamanan siber sedang stabil.</p>
        </div>
        @else
        <div class="warnings-grid">
            @foreach($warnings as $warning)
            <a href="{{ route('warnings.show', $warning->id) }}" class="warning-card">
                {{-- Image --}}
                @if($warning->thumbnail)
                <img src="{{ $warning->thumbnail }}" alt="{{ $warning->title }}" class="warning-card__image" loading="lazy">
                @else
                <div class="warning-card__no-image">
                    <i class="bi bi-image" aria-hidden="true"></i>
                </div>
                @endif

                {{-- Content --}}
                <div class="warning-card__body">
                    <h3 class="warning-card__title">{{ $warning->title }}</h3>
                    <p class="warning-card__excerpt">
                        {{ Str::limit($warning->description, 120) }}
                    </p>

                    <div class="warning-card__footer">
                        <span class="warning-card__cta">
                            Lihat Alert <i class="bi bi-arrow-right" style="font-size: 10px; margin-left: 4px;"></i>
                        </span>
                        <span class="warning-card__date">
                            {{ $warning->date->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection