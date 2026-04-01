{{--
    events/index.blade.php
    Controller: EventController@index
    Variable:   $events  (paginated collection of Event models)

    Card shows: thumbnail, title, date, location only.
    Description is intentionally hidden on the list page.
--}}
@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.events-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.events-header::before {
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
.events-header .container { position: relative; z-index: 1; }
.events-header__eyebrow {
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
.events-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.events-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.events-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
}

/* ============================================================
   LAYOUT
   ============================================================ */
.events-body {
    padding: 56px 0 80px;
    background: var(--mist);
}

/* ============================================================
   EVENT CARDS GRID
   NYC.gov uses 3 columns; we do the same but add thumbnail
   ============================================================ */
.events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border); /* gap color */
    border: 1px solid var(--border);
    margin-bottom: 40px;
}

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

/* Thumbnail — image from seeder/upload */
.event-card__thumb {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    filter: grayscale(15%);
    transition: filter 0.3s ease, transform 0.3s ease;
}
.event-card__thumb-wrap { overflow: hidden; }
.event-card:hover .event-card__thumb {
    filter: grayscale(0%);
    transform: scale(1.03);
}

/* Date badge — overlaid bottom-left of thumbnail */
.event-card__date-badge {
    position: absolute;
    top: 168px; /* thumb height - badge height / overlap */
    left: 0;
    background: var(--navy);
    color: var(--white);
    padding: 6px 14px 6px 16px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 2;
}
.event-card__date-badge i { font-size: 11px; opacity: 0.7; }

/* Card body */
.event-card__body {
    padding: 44px 20px 22px; /* top padding clears the badge overlap */
    flex: 1;
    display: flex;
    flex-direction: column;
}
.event-card__title {
    font-family: var(--font-display);
    font-size: 17px;
    font-weight: 800;
    letter-spacing: 0.01em;
    color: var(--ink);
    line-height: 1.25;
    margin-bottom: 12px;
    transition: color var(--ease);
}
.event-card:hover .event-card__title { color: var(--navy); }

.event-card__meta {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: auto;
}
.event-card__meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--mid);
}
.event-card__meta-item i {
    font-size: 11px;
    color: var(--navy);
    flex-shrink: 0;
    width: 13px;
}
.event-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 14px;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--navy);
}
.event-card__cta i { font-size: 10px; }

/* Empty state */
.events-empty {
    grid-column: 1 / -1;
    padding: 80px 24px;
    text-align: center;
    background: var(--white);
}
.events-empty i {
    font-size: 44px;
    color: var(--border);
    display: block;
    margin-bottom: 14px;
}
.events-empty p { font-size: 15px; color: var(--mid); }

/* ============================================================
   PAGINATION — override Laravel's default to match design system
   ============================================================ */
.events-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 4px;
    margin-top: 8px;
}
/* Laravel pagination uses nav > ul > li > a/span by default */
.events-pagination nav { display: flex; justify-content: center; }
.events-pagination nav ul,
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
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
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

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .events-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .events-grid { grid-template-columns: 1fr; }
    .event-card__date-badge { top: 168px; }
}
</style>


{{-- PAGE HEADER --}}
<div class="events-header">
    <div class="container">
        <div class="events-header__eyebrow">
            <i class="bi bi-calendar-event" aria-hidden="true"></i>
            JakartaProv-CSIRT
        </div>
        <h1>Event &amp; Webinar</h1>
        <p class="events-header__sub">
            Bergabunglah dalam berbagai kegiatan edukasi dan sosialisasi keamanan siber bersama JakartaProv-CSIRT.
        </p>
    </div>
</div>


{{-- EVENTS BODY --}}
<div class="events-body">
    <div class="container">

        <div class="events-grid">
            @forelse($events as $event)
            <a href="{{ route('events.show', $event) }}" class="event-card" aria-label="{{ $event->title }}">

                {{-- Thumbnail --}}
                <div class="event-card__thumb-wrap">
                    <img class="event-card__thumb"
                         src="{{ $event->thumbnail ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80' }}"
                         alt="{{ $event->title }}"
                         onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'">
                </div>

                {{-- Date badge overlaid on thumbnail bottom --}}
                @if($event->event_date)
                <div class="event-card__date-badge">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    {{ $event->event_date->format('d M Y') }}
                </div>
                @endif

                {{-- Card body: title + location only --}}
                <div class="event-card__body">
                    <h3 class="event-card__title">{{ $event->title }}</h3>

                    <div class="event-card__meta">
                        @if($event->location)
                        <div class="event-card__meta-item">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        @endif

                        @if($event->event_type)
                        <div class="event-card__meta-item">
                            <i class="bi bi-tag-fill" aria-hidden="true"></i>
                            <span>{{ ucfirst($event->event_type) }}</span>
                        </div>
                        @endif
                    </div>

                    <span class="event-card__cta">
                        Detail Event <i class="bi bi-arrow-right"></i>
                    </span>
                </div>

            </a>
            @empty
            <div class="events-empty">
                <i class="bi bi-calendar-x" aria-hidden="true"></i>
                <p>Belum ada event. Jalankan seeder: <code>php artisan db:seed --class=EventSeeder</code></p>
            </div>
            @endforelse
        </div>

        {{-- Laravel pagination --}}
        <div class="events-pagination">
            {{ $events->links() }}
        </div>

    </div>
</div>

@endsection