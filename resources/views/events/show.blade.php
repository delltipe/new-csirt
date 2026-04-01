{{--
    events/show.blade.php
    Controller: EventController@show
    Variables:
      $event         — single Event model
      $relatedEvents — Collection of up to 3 other events (from controller)
--}}
@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   EVENT DETAIL — PAGE HEADER
   ============================================================ */
.event-detail-header {
    background: var(--ink);
    padding: 36px 0 0;
    position: relative;
    overflow: hidden;
}
.event-detail-header::before {
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
.event-detail-header .container { position: relative; z-index: 1; }

/* Back link */
.event-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    margin-bottom: 20px;
    transition: color var(--ease);
}
.event-back:hover { color: rgba(255,255,255,0.8); }
.event-back i { font-size: 11px; }

/* Hero grid: text left, thumbnail right */
.event-hero-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 40px;
    align-items: end;
    padding-bottom: 0;
}
.event-hero__title {
    font-family: var(--font-display);
    font-size: clamp(28px, 4.5vw, 48px);
    font-weight: 900;
    letter-spacing: 0.01em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1.0;
    margin-bottom: 20px;
}
.event-hero__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 28px;
}
.event-hero__meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    font-weight: 400;
    color: rgba(255,255,255,0.55);
}
.event-hero__meta-item i {
    color: var(--navy-mid);
    font-size: 14px;
}
.event-hero__meta-item strong {
    color: rgba(255,255,255,0.85);
    font-weight: 600;
}

/* Thumbnail in header — slides up from bottom */
.event-hero__thumb-wrap {
    align-self: flex-end;
}
.event-hero__thumb {
    width: 100%;
    height: 240px;
    object-fit: cover;
    display: block;
    border: 3px solid rgba(255,255,255,0.08);
}

/* ============================================================
   EVENT BODY
   ============================================================ */
.event-body {
    padding: 56px 0 80px;
    background: var(--mist);
}
.event-body-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   MAIN CONTENT — Description card
   ============================================================ */
.event-desc-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--navy);
    padding: 36px 32px;
    margin-bottom: 24px;
}
.event-desc-card__heading {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 900;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.event-desc-card__text {
    font-size: 15px;
    font-weight: 400;
    color: var(--mid);
    line-height: 1.8;
}

/* Registration CTA */
.event-register-card {
    padding: 28px 32px;
    background: var(--navy-dim);
    position: relative;
    overflow: hidden;
}
.event-register-card::before {
    content: '→';
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    font-family: var(--font-display);
    font-size: 120px;
    font-weight: 900;
    color: rgba(255,255,255,0.04);
    line-height: 1;
    pointer-events: none;
}
.event-register-card__title {
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    margin-bottom: 8px;
}
.event-register-card__sub {
    font-size: 14px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    margin-bottom: 20px;
    line-height: 1.6;
}
.btn-register {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    color: var(--navy-dim);
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 900;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 12px 24px;
    text-decoration: none;
    transition: background var(--ease);
}
.btn-register:hover { background: var(--navy-tint); color: var(--navy-dim); }

/* ============================================================
   SIDEBAR
   ============================================================ */
.event-sidebar-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--navy);
    padding: 24px;
    margin-bottom: 16px;
    position: sticky;
    top: calc(68px + 20px);
}
.event-sidebar-card__title {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.event-sidebar-card__title i { color: var(--navy); font-size: 13px; }

.event-summary-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0;
}
.event-summary-item {
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
}
.event-summary-item:last-child { border-bottom: none; padding-bottom: 0; }
.event-summary-item__label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--mid);
    margin-bottom: 3px;
}
.event-summary-item__value {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
    line-height: 1.4;
}

/* ============================================================
   RELATED EVENTS — same card style as the list page
   but horizontal strip at the bottom
   ============================================================ */
.related-section {
    margin-top: 48px;
}
.related-section .section-header {
    margin-bottom: 24px;
}
.related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
}
/* Reuse event-card styles from index page — they're the same component */
.event-card {
    background: var(--white);
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    transition: background var(--ease);
    position: relative;
    overflow: hidden;
}
.event-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--navy);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.2s ease;
}
.event-card:hover { background: var(--navy-tint); }
.event-card:hover::after { transform: scaleX(1); }
.event-card__thumb-wrap { overflow: hidden; }
.event-card__thumb {
    width: 100%; height: 180px; object-fit: cover; display: block;
    filter: grayscale(15%);
    transition: filter 0.3s ease, transform 0.3s ease;
}
.event-card:hover .event-card__thumb { filter: grayscale(0%); transform: scale(1.03); }
.event-card__date-badge {
    position: absolute;
    top: 148px;
    left: 0;
    background: var(--navy);
    color: var(--white);
    padding: 5px 14px 5px 16px;
    font-family: var(--font-display);
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 2;
}
.event-card__date-badge i { font-size: 10px; opacity: 0.7; }
.event-card__body {
    padding: 40px 18px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.event-card__title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    color: var(--ink);
    line-height: 1.25;
    margin-bottom: 10px;
    transition: color var(--ease);
}
.event-card:hover .event-card__title { color: var(--navy); }
.event-card__meta { display: flex; flex-direction: column; gap: 4px; margin-top: auto; }
.event-card__meta-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--mid);
}
.event-card__meta-item i { font-size: 10px; color: var(--navy); width: 12px; flex-shrink: 0; }
.event-card__cta {
    display: inline-flex; align-items: center; gap: 4px;
    margin-top: 12px;
    font-family: var(--font-display); font-size: 11px; font-weight: 700;
    letter-spacing: 0.06em; text-transform: uppercase; color: var(--navy);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .event-hero-grid { grid-template-columns: 1fr; }
    .event-hero__thumb-wrap { display: none; } /* hide thumb in header on mobile, it shows in body */
    .event-body-grid { grid-template-columns: 1fr; }
    .event-sidebar-card { position: static; }
    .related-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .related-grid { grid-template-columns: 1fr; }
    .event-desc-card { padding: 24px 18px; }
}
</style>


{{-- ============================================================
     PAGE HEADER — dark band with title + thumbnail
     ============================================================ --}}
<div class="event-detail-header">
    <div class="container">
        <a href="{{ route('events.index') }}" class="event-back">
            <i class="bi bi-arrow-left"></i> Semua Event
        </a>

        <div class="event-hero-grid">
            {{-- Left: title + meta --}}
            <div>
                <h1 class="event-hero__title">{{ $event->title }}</h1>
                <div class="event-hero__meta">
                    @if($event->event_date)
                    <div class="event-hero__meta-item">
                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                        <strong>{{ $event->event_date->format('d M Y') }}</strong>
                        &nbsp;·&nbsp; {{ $event->event_date->format('H:i') }} WIB
                    </div>
                    @endif
                    @if($event->location)
                    <div class="event-hero__meta-item">
                        <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                    @endif
                    @if($event->event_type)
                    <div class="event-hero__meta-item">
                        <i class="bi bi-tag-fill" aria-hidden="true"></i>
                        <span>{{ ucfirst($event->event_type) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: thumbnail --}}
            <div class="event-hero__thumb-wrap">
                <img class="event-hero__thumb"
                     src="{{ $event->thumbnail ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80' }}"
                     alt="{{ $event->title }}"
                     onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'">
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     EVENT BODY
     ============================================================ --}}
<div class="event-body">
    <div class="container">
        <div class="event-body-grid">

            {{-- ====================================
                 MAIN COLUMN
                 ==================================== --}}
            <div>
                {{-- Description --}}
                <div class="event-desc-card">
                    <h2 class="event-desc-card__heading">
                        <i class="bi bi-file-text" style="color:var(--navy);margin-right:6px;"></i>
                        Tentang Event
                    </h2>
                    <p class="event-desc-card__text">
                        {{ $event->description ?? 'Deskripsi event belum tersedia.' }}
                    </p>
                </div>

                {{-- Registration CTA (only if URL exists) --}}
                @if($event->registration_url)
                <div class="event-register-card">
                    <div class="event-register-card__title">Tertarik Mengikuti?</div>
                    <p class="event-register-card__sub">
                        Daftar sekarang untuk mengikuti event ini dan dapatkan akses ke materi eksklusif.
                    </p>
                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener" class="btn-register">
                        <i class="bi bi-box-arrow-up-right"></i> Daftar Event
                    </a>
                </div>
                @endif

                {{-- Related events strip --}}
                @if($relatedEvents->count())
                <div class="related-section">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">Event Lainnya</h2>
                            <p class="section-subtitle">Kegiatan edukasi dari JakartaProv-CSIRT</p>
                        </div>
                        <a href="{{ route('events.index') }}" class="section-link">
                            Lihat Semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="related-grid">
                        @foreach($relatedEvents as $related)
                        <a href="{{ route('events.show', $related) }}" class="event-card">
                            <div class="event-card__thumb-wrap">
                                <img class="event-card__thumb"
                                     src="{{ $related->thumbnail ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80' }}"
                                     alt="{{ $related->title }}"
                                     onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'">
                            </div>
                            @if($related->event_date)
                            <div class="event-card__date-badge">
                                <i class="bi bi-calendar3"></i>
                                {{ $related->event_date->format('d M Y') }}
                            </div>
                            @endif
                            <div class="event-card__body">
                                <h3 class="event-card__title">{{ $related->title }}</h3>
                                <div class="event-card__meta">
                                    @if($related->location)
                                    <div class="event-card__meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $related->location }}</span>
                                    </div>
                                    @endif
                                </div>
                                <span class="event-card__cta">Detail <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>{{-- /main column --}}


            {{-- ====================================
                 SIDEBAR
                 ==================================== --}}
            <aside>
                <div class="event-sidebar-card">
                    <div class="event-sidebar-card__title">
                        <i class="bi bi-info-circle-fill"></i>
                        Ringkasan Event
                    </div>
                    <ul class="event-summary-list">
                        @if($event->event_date)
                        <li class="event-summary-item">
                            <div class="event-summary-item__label">Tanggal</div>
                            <div class="event-summary-item__value">
                                {{ $event->event_date->format('l, d M Y') }}
                            </div>
                        </li>
                        <li class="event-summary-item">
                            <div class="event-summary-item__label">Waktu</div>
                            <div class="event-summary-item__value">
                                {{ $event->event_date->format('H:i') }} WIB
                            </div>
                        </li>
                        @endif
                        @if($event->location)
                        <li class="event-summary-item">
                            <div class="event-summary-item__label">Lokasi</div>
                            <div class="event-summary-item__value">{{ $event->location }}</div>
                        </li>
                        @endif
                        @if($event->event_type)
                        <li class="event-summary-item">
                            <div class="event-summary-item__label">Jenis Event</div>
                            <div class="event-summary-item__value">{{ ucfirst($event->event_type) }}</div>
                        </li>
                        @endif
                        @if($event->capacity)
                        <li class="event-summary-item">
                            <div class="event-summary-item__label">Kapasitas</div>
                            <div class="event-summary-item__value">{{ $event->capacity }} Peserta</div>
                        </li>
                        @endif
                    </ul>

                    @if($event->registration_url)
                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener"
                       style="display:block;margin-top:20px;text-align:center;"
                       class="btn-register">
                        <i class="bi bi-box-arrow-up-right"></i> Daftar Sekarang
                    </a>
                    @endif
                </div>
            </aside>

        </div>
    </div>
</div>

@endsection