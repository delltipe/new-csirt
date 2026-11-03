@extends('layouts.app')

@section('content')

@include('secaw.partials.styles')

{{-- ============================================================
     HEADER BAND
     ============================================================ --}}
<header class="secaw-header" aria-label="Header Materi Edukasi">
    <div class="container">
        <p class="secaw-header__eyebrow">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            Security Awareness • Modul Edukasi
        </p>
        <h1>Materi & Panduan Teknis</h1>
        <p class="secaw-header__sub">
            Kumpulan dokumen panduan resmi, standar operasional prosedur (SOP), dan modul literasi keamanan siber yang diterbitkan oleh CSIRT DKI Jakarta serta BSSN.
        </p>
    </div>
</header>

{{-- ============================================================
     BREADCRUMB & SUBNAV
     ============================================================ --}}
@include('secaw.partials.subnav', ['activeTitle' => 'Materi'])

{{-- ============================================================
     MAIN BODY
     ============================================================ --}}
<div class="secaw-shell" id="main-content">
    <div class="container">

        {{-- Filter & Search Box --}}
        <div class="secaw-filter-box" role="search" aria-label="Pencarian Materi Edukasi">
            <p class="secaw-filter-box__stats">
                <i class="bi bi-collection text-primary" aria-hidden="true"></i>
                <span>Menampilkan <strong>{{ $guides->total() }}</strong> dokumen materi edukasi terverifikasi.</span>
            </p>

            <form method="GET" action="{{ route('secaw.materi') }}" class="secaw-search-form" autocomplete="off">
                <div class="secaw-input-group">
                    <i class="bi bi-search secaw-input-icon" aria-hidden="true"></i>
                    <input type="text"
                           name="search"
                           class="secaw-input"
                           placeholder="Cari judul materi atau penerbit..."
                           value="{{ $search }}"
                           aria-label="Cari judul materi atau penerbit">
                </div>
                <button type="submit" class="secaw-btn secaw-btn--primary">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Cari
                </button>
                @if($search !== '')
                    <a href="{{ route('secaw.materi') }}" class="secaw-btn secaw-btn--secondary" title="Reset pencarian">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Cards Grid --}}
        @if($guides->isNotEmpty())
            <div class="secaw-grid-3" aria-label="Daftar Materi Keamanan Siber">
                @foreach($guides as $guide)
                    <article class="secaw-card" aria-labelledby="guide-title-{{ $guide->id }}">
                        <div class="secaw-card__body">
                            <div>
                                <span class="secaw-badge secaw-badge--info">
                                    <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                    Dokumen PDF
                                </span>
                            </div>

                            <h3 class="secaw-card__title" id="guide-title-{{ $guide->id }}">
                                {{ $guide->title }}
                            </h3>

                            <div class="secaw-card__meta">
                                <div class="secaw-card__meta-item">
                                    <i class="bi bi-building" aria-hidden="true"></i>
                                    <span><strong>Penerbit:</strong> {{ $guide->author ?? 'Pemerintah Provinsi DKI Jakarta' }}</span>
                                </div>
                                @if($guide->created_at)
                                    <div class="secaw-card__meta-item">
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                        <span><strong>Tanggal:</strong> {{ $guide->created_at->translatedFormat('d F Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <p class="secaw-card__desc">
                                Pedoman teknis dan tata kelola pengamanan informasi untuk mendukung kesadaran siber di lingkungan kerja dan sistem elektronik daerah.
                            </p>
                        </div>

                        <div class="secaw-card__footer">
                            <a href="{{ $guide->link }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="secaw-btn secaw-btn--primary"
                               aria-label="Unduh Dokumen PDF: {{ $guide->title }}">
                                <i class="bi bi-download" aria-hidden="true"></i>
                                <span>Unduh Dokumen (PDF)</span>
                            </a>
                            <a href="{{ route('guides.index') }}"
                               class="secaw-subnav__ext"
                               title="Lihat di katalog panduan teknis utama">
                                <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                <span class="d-none d-sm-inline">Katalog</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($guides->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $guides->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="secaw-empty-state" role="status" aria-label="Tidak ada materi ditemukan">
                <i class="bi bi-file-earmark-x secaw-empty-state__icon" aria-hidden="true"></i>
                <h3>Tidak Ada Materi Ditemukan</h3>
                @if($search !== '')
                    <p>Tidak ditemukan materi edukasi atau dokumen yang cocok dengan kata kunci pencarian "<strong>{{ $search }}</strong>".</p>
                    <a href="{{ route('secaw.materi') }}" class="secaw-btn secaw-btn--secondary">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Kembali ke Semua Materi
                    </a>
                @else
                    <p>Belum ada materi edukasi yang tersedia saat ini.</p>
                @endif
            </div>
        @endif

    </div>
</div>

@endsection
