@extends('layouts.app')

@section('content')

@include('secaw.partials.styles')

{{-- ============================================================
     HEADER BAND
     ============================================================ --}}
<header class="secaw-header" aria-label="Header Tools Keamanan Siber">
    <div class="container">
        <p class="secaw-header__eyebrow">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            Security Awareness • Direktori Utilitas
        </p>
        <h1>Tools Keamanan Siber</h1>
        <p class="secaw-header__sub">
            Direktori utilitas pengujian kesadaran phishing, pemantauan kebocoran data (data breach), dan intelijen ancaman siber publik yang terverifikasi untuk evaluasi mandiri.
        </p>
    </div>
</header>

{{-- ============================================================
     BREADCRUMB & SUBNAV
     ============================================================ --}}
@include('secaw.partials.subnav', ['activeTitle' => 'Tools Cybersecurity'])

{{-- ============================================================
     MAIN BODY
     ============================================================ --}}
<main class="secaw-shell" id="main-content">
    <div class="container">

        {{-- Crucial Security & Confidentiality Disclaimer Banner --}}
        <div class="secaw-alert secaw-alert--warning" role="alert" aria-labelledby="warning-heading">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            <div class="secaw-alert__content">
                <strong id="warning-heading">Peringatan Keamanan & Kebijakan Data Rahasia Pemerintah:</strong>
                Pengunjung dilarang keras mengunggah dokumen kedinasan berklasifikasi, informasi rahasia pemerintah daerah, data pribadi sensitif (NIK, rekam medis, data keuangan), bukti forensik insiden siber yang sedang ditangani, atau kredensial autentikasi sistem ke layanan pihak ketiga mana pun. Seluruh perangkat eksternal di bawah ini disediakan semata-mata sebagai sarana edukasi literasi dan penapisan awal mandiri. Pengguna akan diarahkan keluar dari portal resmi CSIRT DKI Jakarta ke situs penyedia masing-masing.
            </div>
        </div>

        {{-- Filter & Search Box --}}
        <div class="secaw-filter-box" role="search" aria-label="Pencarian Direktori Tools">
            <p class="secaw-filter-box__stats">
                <i class="bi bi-wrench-adjustable text-primary" aria-hidden="true"></i>
                <span>Menampilkan <strong>{{ count($tools) }}</strong> perangkat keamanan siber terverifikasi.</span>
            </p>

            <form method="GET" action="{{ route('secaw.tools') }}" class="secaw-search-form" autocomplete="off">
                <div>
                    <label for="category-select" class="visually-hidden">Kategori Tools</label>
                    <select id="category-select" name="kategori" class="secaw-select" onchange="this.form.submit()">
                        @foreach($categories as $code => $label)
                            <option value="{{ $code }}" {{ $selectedCategory === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="secaw-input-group">
                    <i class="bi bi-search secaw-input-icon" aria-hidden="true"></i>
                    <input type="text"
                           name="search"
                           class="secaw-input"
                           placeholder="Cari nama perangkat atau fungsi..."
                           value="{{ $search }}"
                           aria-label="Cari nama alat atau fungsi">
                </div>

                <button type="submit" class="secaw-btn secaw-btn--primary">
                    <i class="bi bi-funnel" aria-hidden="true"></i>
                    Filter
                </button>

                @if($search !== '' || $selectedCategory !== 'all')
                    <a href="{{ route('secaw.tools') }}" class="secaw-btn secaw-btn--secondary" title="Reset filter">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Tools Cards Grid --}}
        @if(count($tools) > 0)
            <div class="secaw-grid-3" aria-label="Daftar Perangkat Keamanan Siber">
                @foreach($tools as $tool)
                    <article class="secaw-card" aria-labelledby="tool-title-{{ Str::slug($tool['name']) }}">
                        <div class="secaw-card__body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="secaw-badge secaw-badge--info">
                                    <i class="bi bi-tag" aria-hidden="true"></i>
                                    {{ $tool['category'] }}
                                </span>
                                <span class="secaw-badge secaw-badge--available">
                                    {{ $tool['badge'] }}
                                </span>
                            </div>

                            <h3 class="secaw-card__title" id="tool-title-{{ Str::slug($tool['name']) }}">
                                {{ $tool['name'] }}
                            </h3>

                            <div class="secaw-card__meta">
                                <div class="secaw-card__meta-item">
                                    <i class="bi bi-globe2" aria-hidden="true"></i>
                                    <span><strong>Penyedia:</strong> {{ $tool['provider'] }}</span>
                                </div>
                            </div>

                            <p class="secaw-card__desc">
                                {{ $tool['description'] }}
                            </p>
                        </div>

                        <div class="secaw-card__footer">
                            <a href="{{ $tool['url'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="secaw-btn secaw-btn--primary"
                               aria-label="Buka {{ $tool['name'] }} di tab baru (Keluar dari situs CSIRT)">
                                <span>Akses Perangkat</span>
                                <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                            </a>
                            <span class="secaw-external-tag" title="Membuka situs web pihak ketiga">
                                <i class="bi bi-shield-exclamation text-warning" aria-hidden="true"></i>
                                Pihak Ketiga
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="secaw-empty-state" role="status" aria-label="Tidak ada perangkat ditemukan">
                <i class="bi bi-wrench secaw-empty-state__icon" aria-hidden="true"></i>
                <h3>Tidak Ada Perangkat Ditemukan</h3>
                <p>Tidak ditemukan perangkat keamanan siber yang cocok dengan kriteria filter atau pencarian Anda.</p>
                <a href="{{ route('secaw.tools') }}" class="secaw-btn secaw-btn--secondary">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Kembali ke Semua Perangkat
                </a>
            </div>
        @endif

    </div>
</main>

@endsection
