@extends('layouts.app')

@section('content')

@include('secaw.partials.styles')

{{-- ============================================================
     HEADER BAND
     ============================================================ --}}
<header class="secaw-header" aria-label="Header Infografis Edukasi">
    <div class="container">
        <p class="secaw-header__eyebrow">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            Security Awareness • Visual Edukasi
        </p>
        <h1>Infografis Keamanan Siber</h1>
        <p class="secaw-header__sub">
            Media edukasi visual seputar pencegahan ancaman digital, etika privasi, keamanan perangkat, serta panduan praktis pelindungan data personal bagi ASN dan masyarakat DKI Jakarta.
        </p>
    </div>
</header>

{{-- ============================================================
     BREADCRUMB & SUBNAV
     ============================================================ --}}
@include('secaw.partials.subnav', ['activeTitle' => 'Infografis'])

{{-- ============================================================
     MAIN BODY
     ============================================================ --}}
<main class="secaw-shell" id="main-content">
    <div class="container">

        {{-- Filter & Search Box --}}
        <div class="secaw-filter-box" role="search" aria-label="Pencarian Infografis">
            <p class="secaw-filter-box__stats">
                <i class="bi bi-images text-primary" aria-hidden="true"></i>
                <span>Menampilkan <strong>{{ $infographics->total() }}</strong> infografis edukasi keamanan siber.</span>
            </p>

            <form method="GET" action="{{ route('secaw.infografis') }}" class="secaw-search-form" autocomplete="off">
                <div class="secaw-input-group">
                    <i class="bi bi-search secaw-input-icon" aria-hidden="true"></i>
                    <input type="text" 
                           name="search" 
                           class="secaw-input" 
                           placeholder="Cari judul infografis..." 
                           value="{{ $search }}" 
                           aria-label="Cari judul infografis">
                </div>
                <button type="submit" class="secaw-btn secaw-btn--primary">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Cari
                </button>
                @if($search !== '')
                    <a href="{{ route('secaw.infografis') }}" class="secaw-btn secaw-btn--secondary" title="Reset pencarian">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Infographics Grid --}}
        @if($infographics->isNotEmpty())
            <div class="secaw-grid-3" aria-label="Galeri Infografis Keamanan">
                @foreach($infographics as $index => $item)
                    <button type="button" 
                            class="secaw-infographic-card" 
                            data-img="{{ $item->thumbnail }}" 
                            data-title="{{ $item->title }}"
                            data-index="{{ $index }}"
                            aria-haspopup="dialog" 
                            aria-label="Lihat infografis: {{ $item->title }}">
                        <div class="secaw-infographic-img-wrap">
                            <img src="{{ $item->thumbnail }}" 
                                 alt="{{ $item->title }}" 
                                 class="secaw-infographic-img"
                                 loading="lazy">
                            <div class="secaw-card-overlay" aria-hidden="true">
                                <span class="secaw-zoom-btn">
                                    <i class="bi bi-zoom-in"></i>
                                </span>
                            </div>
                        </div>

                        <div class="secaw-card__body">
                            <div>
                                <span class="secaw-badge secaw-badge--info">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    Edukasi Visual
                                </span>
                            </div>

                            <h3 class="secaw-card__title">
                                {{ $item->title }}
                            </h3>

                            @if($item->created_at)
                                <div class="secaw-card__meta">
                                    <div class="secaw-card__meta-item">
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                        <span>{{ $item->created_at->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="secaw-card__footer">
                            <span class="secaw-btn secaw-btn--secondary w-100 justify-content-center" style="pointer-events: none;">
                                <i class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                                <span>Perbesar Gambar</span>
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($infographics->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $infographics->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="secaw-empty-state" role="status" aria-label="Tidak ada infografis ditemukan">
                <i class="bi bi-image secaw-empty-state__icon" aria-hidden="true"></i>
                <h3>Tidak Ada Infografis Ditemukan</h3>
                @if($search !== '')
                    <p>Tidak ditemukan infografis yang cocok dengan kata kunci pencarian "<strong>{{ $search }}</strong>".</p>
                    <a href="{{ route('secaw.infografis') }}" class="secaw-btn secaw-btn--secondary">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Kembali ke Semua Infografis
                    </a>
                @else
                    <p>Belum ada materi infografis yang terdaftar dalam galeri saat ini.</p>
                @endif
            </div>
        @endif

    </div>
</main>

{{-- ============================================================
     ACCESSIBLE MODAL DIALOG (Modern Web Guidance Standard)
     ============================================================ --}}
<dialog id="infographic-dialog" class="secaw-dialog" aria-labelledby="dialog-title" closedby="any">
    <div class="secaw-dialog__header">
        <h2 id="dialog-title" class="secaw-dialog__title">Pratinjau Infografis</h2>
        <button type="button" id="dialog-close" class="secaw-dialog__close" aria-label="Tutup Pratinjau">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>
    </div>

    <div class="secaw-dialog__body">
        <img id="dialog-img" src="" alt="" class="secaw-dialog__img">
    </div>

    <div class="secaw-dialog__footer">
        <span id="dialog-counter" class="text-muted" style="font-size: 13px; font-weight: 600;"></span>
        <div class="d-flex gap-2">
            <button type="button" id="dialog-prev" class="secaw-btn secaw-btn--secondary" aria-label="Infografis Sebelumnya">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">Sebelumnya</span>
            </button>
            <button type="button" id="dialog-next" class="secaw-btn secaw-btn--secondary" aria-label="Infografis Berikutnya">
                <span class="d-none d-sm-inline">Berikutnya</span>
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
            </button>
            <a id="dialog-full-link" href="#" target="_blank" rel="noopener noreferrer" class="secaw-btn secaw-btn--primary">
                <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                <span>Buka Ukuran Penuh</span>
            </a>
        </div>
    </div>
</dialog>

<script>
(function() {
    'use strict';

    const dialog = document.getElementById('infographic-dialog');
    if (!dialog) return;

    const dialogTitle = document.getElementById('dialog-title');
    const dialogImg = document.getElementById('dialog-img');
    const dialogClose = document.getElementById('dialog-close');
    const dialogPrev = document.getElementById('dialog-prev');
    const dialogNext = document.getElementById('dialog-next');
    const dialogFullLink = document.getElementById('dialog-full-link');
    const dialogCounter = document.getElementById('dialog-counter');

    const cards = Array.from(document.querySelectorAll('.secaw-infographic-card'));
    let currentIndex = 0;
    let lastActiveElement = null;

    function showItem(index) {
        if (!cards.length) return;
        currentIndex = (index + cards.length) % cards.length;
        const card = cards[currentIndex];
        const title = card.getAttribute('data-title') || 'Infografis';
        const imgUrl = card.getAttribute('data-img') || '';

        dialogTitle.textContent = title;
        dialogImg.src = imgUrl;
        dialogImg.alt = title;
        dialogFullLink.href = imgUrl;
        dialogCounter.textContent = (currentIndex + 1) + ' dari ' + cards.length;

        // Hide prev/next if only 1 item
        if (cards.length <= 1) {
            dialogPrev.style.display = 'none';
            dialogNext.style.display = 'none';
        } else {
            dialogPrev.style.display = '';
            dialogNext.style.display = '';
        }
    }

    function openDialog(index) {
        lastActiveElement = document.activeElement;
        showItem(index);
        if (typeof dialog.showModal === 'function') {
            dialog.showModal();
        } else {
            dialog.setAttribute('open', '');
        }
        dialogClose.focus();
    }

    function closeDialog() {
        if (typeof dialog.close === 'function') {
            dialog.close();
        } else {
            dialog.removeAttribute('open');
        }
        if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
            lastActiveElement.focus();
        }
    }

    // Attach click listeners to cards
    cards.forEach((card, idx) => {
        card.addEventListener('click', function() {
            openDialog(idx);
        });
    });

    // Close button
    dialogClose.addEventListener('click', closeDialog);

    // Navigation buttons
    dialogPrev.addEventListener('click', function() {
        showItem(currentIndex - 1);
    });

    dialogNext.addEventListener('click', function() {
        showItem(currentIndex + 1);
    });

    // Close on backdrop click
    dialog.addEventListener('click', function(e) {
        const rect = dialog.getBoundingClientRect();
        const isInDialog = (
            rect.top <= e.clientY && e.clientY <= rect.top + rect.height &&
            rect.left <= e.clientX && e.clientX <= rect.left + rect.width
        );
        if (!isInDialog) {
            closeDialog();
        }
    });

    // Keyboard navigation: Escape is handled natively, Arrow keys navigate
    dialog.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            showItem(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            showItem(currentIndex + 1);
        }
    });
})();
</script>

@endsection
