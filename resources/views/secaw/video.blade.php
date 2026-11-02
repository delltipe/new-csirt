@extends('layouts.app')

@section('content')

@include('secaw.partials.styles')

{{-- ============================================================
     HEADER BAND
     ============================================================ --}}
<header class="secaw-header" aria-label="Header Video Sosialisasi">
    <div class="container">
        <p class="secaw-header__eyebrow">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            Security Awareness • Audio Visual
        </p>
        <h1>Video & Sosialisasi Siber</h1>
        <p class="secaw-header__sub">
            Kumpulan rekaman webinar literasi digital, pemaparan praktisi keamanan informasi, dan panduan mitigasi ancaman teknologi bagi aparatur serta publik DKI Jakarta.
        </p>
    </div>
</header>

{{-- ============================================================
     BREADCRUMB & SUBNAV
     ============================================================ --}}
@include('secaw.partials.subnav', ['activeTitle' => 'Video'])

{{-- ============================================================
     MAIN BODY
     ============================================================ --}}
<main class="secaw-shell" id="main-content">
    <div class="container">

        {{-- Filter & Search Box --}}
        <div class="secaw-filter-box" role="search" aria-label="Pencarian Video Sosialisasi">
            <p class="secaw-filter-box__stats">
                <i class="bi bi-play-circle text-primary" aria-hidden="true"></i>
                <span>Menampilkan <strong>{{ count($videos) }}</strong> rekaman sosialisasi terverifikasi.</span>
            </p>

            <form method="GET" action="{{ route('secaw.video') }}" class="secaw-search-form" autocomplete="off">
                <div class="secaw-input-group">
                    <i class="bi bi-search secaw-input-icon" aria-hidden="true"></i>
                    <input type="text"
                           name="search"
                           class="secaw-input"
                           placeholder="Cari judul video, narasumber, atau topik..."
                           value="{{ $search }}"
                           aria-label="Cari judul video atau narasumber">
                </div>
                <button type="submit" class="secaw-btn secaw-btn--primary">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Cari
                </button>
                @if($search !== '')
                    <a href="{{ route('secaw.video') }}" class="secaw-btn secaw-btn--secondary" title="Reset pencarian">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Video Provenance & Educational Reference Notice --}}
        <div class="secaw-alert" role="note" aria-label="Keterangan Sumber Video" style="margin-bottom: 24px;">
            <i class="bi bi-info-circle text-primary fs-4" style="flex-shrink: 0; margin-top: 2px;" aria-hidden="true"></i>
            <div class="secaw-alert__content">
                <strong style="color: var(--navy); display: block; margin-bottom: 4px; font-family: var(--font-display); text-transform: uppercase; letter-spacing: 0.02em;">Sumber Edukasi &amp; Referensi Sosialisasi</strong>
                Seluruh rekaman video sosialisasi di bawah ini merupakan dokumentasi literasi keamanan informasi resmi dari <strong>Suku Dinas Kominfotik Kota Administrasi Jakarta Barat</strong> sebagai materi referensi edukasi, bukan materi yang diproduksi secara langsung oleh CSIRT Pemerintah Provinsi DKI Jakarta.
            </div>
        </div>

        {{-- Video Cards Grid --}}
        @if(count($videos) > 0)
            <div class="secaw-grid-2" aria-label="Daftar Video Sosialisasi Keamanan Siber">
                @foreach($videos as $video)
                    <article class="secaw-video-card" aria-labelledby="video-title-{{ $video['id'] }}">
                        {{-- Privacy-Conscious Click-To-Load Player Container --}}
                        <div class="secaw-video-facade-container" id="player-container-{{ $video['id'] }}">
                            <img src="{{ $video['thumbnail'] }}"
                                 alt="Thumbnail video: {{ $video['title'] }}"
                                 class="secaw-video-facade-img"
                                 loading="lazy">
                            <button type="button"
                                    class="secaw-video-play-btn"
                                    data-video-id="{{ $video['id'] }}"
                                    data-video-title="{{ $video['title'] }}"
                                    data-container-id="player-container-{{ $video['id'] }}"
                                    aria-label="Putar video: {{ $video['title'] }}">
                                <span class="secaw-video-play-icon" aria-hidden="true">
                                    <i class="bi bi-play-fill"></i>
                                </span>
                                <span class="secaw-video-play-label">Putar Video (16:9)</span>
                            </button>
                        </div>

                        <div class="secaw-card__body">
                            <div>
                                <span class="secaw-badge secaw-badge--info">
                                    <i class="bi bi-broadcast" aria-hidden="true"></i>
                                    {{ $video['series'] }}
                                </span>
                            </div>

                            <h3 class="secaw-card__title" id="video-title-{{ $video['id'] }}">
                                {{ $video['title'] }}
                            </h3>

                            <div class="secaw-card__meta">
                                <div class="secaw-card__meta-item">
                                    <i class="bi bi-person-badge" aria-hidden="true"></i>
                                    <span><strong>Narasumber:</strong> {{ $video['speaker'] }}</span>
                                </div>
                                <div class="secaw-card__meta-item">
                                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                    <span><strong>Tanggal:</strong> {{ $video['date'] }}</span>
                                </div>
                            </div>

                            <p class="secaw-card__desc">
                                {{ $video['description'] }}
                            </p>
                        </div>

                        <div class="secaw-card__footer">
                            <a href="{{ $video['url'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="secaw-btn secaw-btn--secondary"
                               aria-label="Tonton di YouTube Resmi: {{ $video['title'] }}">
                                <i class="bi bi-youtube text-danger" aria-hidden="true"></i>
                                <span>Buka di YouTube</span>
                            </a>
                            <span class="secaw-external-tag">
                                <i class="bi bi-shield-check text-success" aria-hidden="true"></i>
                                Kanal Resmi Kota Administrasi Jakarta Barat
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="secaw-empty-state" role="status" aria-label="Tidak ada video ditemukan">
                <i class="bi bi-camera-video-off secaw-empty-state__icon" aria-hidden="true"></i>
                <h3>Tidak Ada Video Ditemukan</h3>
                @if($search !== '')
                    <p>Tidak ditemukan rekaman webinar atau sosialisasi yang cocok dengan kata kunci "<strong>{{ $search }}</strong>".</p>
                    <a href="{{ route('secaw.video') }}" class="secaw-btn secaw-btn--secondary">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Kembali ke Semua Video
                    </a>
                @else
                    <p>Katalog video sosialisasi saat ini sedang disiapkan.</p>
                @endif
            </div>
        @endif

    </div>
</main>

<script>
(function() {
    'use strict';

    // Click-to-load video player facade
    const playButtons = document.querySelectorAll('.secaw-video-play-btn');

    playButtons.forEach(button => {
        button.addEventListener('click', function() {
            const videoId = this.getAttribute('data-video-id');
            const videoTitle = this.getAttribute('data-video-title') || 'Video YouTube';
            const containerId = this.getAttribute('data-container-id');
            const container = document.getElementById(containerId);

            if (!container || !videoId) return;

            // Create responsive, privacy-enhanced YouTube embed
            const iframe = document.createElement('iframe');
            iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(videoId) + '?autoplay=1&rel=0';
            iframe.title = videoTitle;
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.allowFullscreen = true;
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.style.border = '0';
            iframe.style.display = 'block';

            // Replace facade with iframe
            container.innerHTML = '';
            container.appendChild(iframe);
            iframe.focus();
        });
    });
})();
</script>

@endsection
