@extends('layouts.app')

@section('content')
@php
    $reports = [
        1 => [
            'title' => 'Ringkasan Serangan Siber',
            'period' => 'Arsip laporan 14 Mei 2025',
            'image' => 'images/honeypot/rekap-serangan-2025-05-14.jpg',
            'alt' => 'Infografis ringkasan serangan siber dengan peta negara sumber serangan dan jumlah serangan per negara.',
        ],
        2 => [
            'title' => 'Statistik Honeypot',
            'period' => 'Data 2021 sampai 2022',
            'image' => 'images/honeypot/statistik-honeypot-2021-2022.jpg',
            'alt' => 'Dasbor statistik honeypot 2021 sampai 2022 dengan peta serangan dunia, grafik malware, dan peta Indonesia.',
        ],
    ];
    $report = $reports[$page];
@endphp

<style>
    .statistics-header {
        background: var(--ink);
        padding: 52px 0 44px;
        position: relative;
    }

    .statistics-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: repeating-linear-gradient(90deg, var(--ink) 0, var(--ink) 79px, var(--navy-dim) 80px);
        opacity: 0.28;
        pointer-events: none;
    }

    .statistics-header .container { position: relative; z-index: 1; }
    .statistics-eyebrow { color: var(--muted-on-dark); font-family: var(--font-display); font-size: 11px; font-weight: 700; letter-spacing: 0.12em; margin-bottom: 10px; text-transform: uppercase; }
    .statistics-header h1 { color: var(--white); font-family: var(--font-display); font-size: clamp(32px, 5vw, 54px); font-weight: 800; letter-spacing: 0.02em; line-height: 1; margin: 0; text-transform: uppercase; }
    .statistics-header p:last-child { color: var(--muted-on-dark); margin: 12px 0 0; }
    .statistics-shell { background: var(--mist); padding: 48px 0 72px; }
    .statistics-intro { border-left: 4px solid var(--navy); color: var(--ink); margin: 0 auto 28px; max-width: 920px; padding: 18px 20px; background: var(--navy-tint); }
    .statistics-report { background: var(--white); border: 1px solid var(--border); margin: 0 auto; max-width: 920px; padding: 28px; }
    .statistics-report__heading { align-items: start; border-bottom: 1px solid var(--border); display: flex; gap: 20px; justify-content: space-between; margin-bottom: 24px; padding-bottom: 18px; }
    .statistics-report h2 { color: var(--ink); font-family: var(--font-display); font-size: 24px; font-weight: 800; line-height: 1.25; margin: 0; }
    .statistics-report__period { color: var(--mid); font-size: 14px; margin: 5px 0 0; }
    .statistics-report img { border: 1px solid var(--border); height: auto; width: 100%; }
    .statistics-pagination { display: flex; gap: 8px; justify-content: center; margin-top: 28px; }
    .statistics-pagination a { align-items: center; border: 1px solid var(--border); color: var(--navy); display: inline-flex; font-family: var(--font-display); font-size: 13px; font-weight: 700; justify-content: center; min-height: 40px; min-width: 40px; padding: 0 14px; transition: background var(--ease), color var(--ease), border-color var(--ease); }
    .statistics-pagination a:hover { border-color: var(--navy); background: var(--navy-tint); }
    .statistics-pagination a[aria-current="page"] { background: var(--navy); border-color: var(--navy); color: var(--white); }
    @media (max-width: 640px) {
        .statistics-shell { padding: 28px 0 48px; }
        .statistics-report { padding: 18px; }
        .statistics-report__heading { display: block; }
        .statistics-pagination { gap: 6px; }
    }
</style>

<header class="statistics-header">
    <div class="container">
        <p class="statistics-eyebrow">Arsip Publikasi</p>
        <h1>Statistik Honeypot</h1>
        <p>Arsip visual aktivitas honeypot JakartaProv-CSIRT.</p>
    </div>
</header>

<section class="statistics-shell">
    <div class="container">
        <p class="statistics-intro"><strong>Catatan arsip:</strong> laporan berikut merupakan gambar statistik yang telah dipublikasikan pada periode masing-masing dan bukan data telemetri langsung.</p>
        <article class="statistics-report">
            <div class="statistics-report__heading">
                <div>
                    <h2>{{ $report['title'] }}</h2>
                    <p class="statistics-report__period">{{ $report['period'] }}</p>
                </div>
            </div>
            <img src="{{ asset($report['image']) }}" alt="{{ $report['alt'] }}">
        </article>
        <nav class="statistics-pagination" aria-label="Navigasi arsip statistik honeypot">
            @foreach ($reports as $number => $item)
                <a href="{{ route('statistics', ['page' => $number]) }}" @if ($number === $page) aria-current="page" @endif aria-label="{{ $item['title'] }}, {{ $item['period'] }}">{{ $number }}</a>
            @endforeach
        </nav>
    </div>
</section>
@endsection
